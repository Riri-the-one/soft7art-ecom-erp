<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\DashboardService;
use App\Services\OrderStatusService;
use App\Services\StatisticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ErpTest extends TestCase
{
    use RefreshDatabase;

    private function order(int $stock = 5): array
    {
        $user = User::factory()->create(['role' => 'agent']);
        $this->actingAs($user);
        $product = Product::factory()->create(['stock_quantity' => $stock, 'purchase_price' => 10, 'selling_price' => 25]);
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'total_amount' => 80, 'delivery_fee' => 30]);
        $order->products()->attach($product, ['quantity' => 2, 'unit_price' => 25, 'unit_cost' => 10]);

        return [$order, $product];
    }

    public function test_confirmation_is_idempotent_even_with_stale_models(): void
    {
        [$order, $product] = $this->order();
        $stale = $order->fresh();
        $service = app(OrderStatusService::class);
        $this->assertTrue($service->transition($order, 'confirmed'));
        $this->assertFalse($service->transition($stale, 'confirmed'));
        $this->assertSame(3, $product->fresh()->stock_quantity);
        $this->assertSame(1, $order->activityLogs()->count());
        $service->transition($order, 'canceled');
        $service->transition($order, 'canceled');
        $this->assertSame(5, $product->fresh()->stock_quantity);
        $this->patchJson(route('orders.updateStatus', $order), ['status' => 'confirmed'])->assertUnprocessable();
    }

    public function test_insufficient_stock_rolls_back_every_line_and_status(): void
    {
        [$order, $product] = $this->order();
        $empty = Product::factory()->create(['stock_quantity' => 0]);
        $order->products()->attach($empty, ['quantity' => 1, 'unit_price' => 20]);
        $this->patchJson(route('orders.updateStatus', $order), ['status' => 'confirmed'])->assertUnprocessable();
        $this->assertSame('pending', $order->fresh()->status);
        $this->assertSame(5, $product->fresh()->stock_quantity);
        $this->assertSame(0, $order->activityLogs()->count());
    }

    public function test_lifecycle_restores_stock_once_and_preserves_cost_snapshot(): void
    {
        [$order, $product] = $this->order();
        $service = app(OrderStatusService::class);
        foreach (['confirmed', 'shipped', 'delivered'] as $status) {
            $service->transition($order, $status);
        }
        $product->update(['purchase_price' => 100]);
        $stats = app(StatisticsService::class)->forFilter('all');
        $this->assertEquals(80, $stats['totalRevenue']);
        $this->assertEquals(30, $stats['productMargin']);
        $service->transition($order, 'returned');
        $service->transition($order, 'returned');
        $this->assertSame(5, $product->fresh()->stock_quantity);
        $this->assertEquals(0, app(StatisticsService::class)->forFilter('all')['totalRevenue']);
    }

    public function test_order_creation_uses_server_prices_and_snapshot(): void
    {
        [$order, $product] = $this->order();
        $this->post(route('orders.store'), ['customer_id' => $order->customer_id, 'product_id' => $product->id, 'quantity' => 2, 'delivery_fee' => 10, 'total_amount' => 1])->assertRedirect();
        $created = Order::latest('id')->first();
        $this->assertEquals(60, $created->total_amount);
        $this->assertEquals(10, $created->products->first()->pivot->unit_cost);
        $this->assertSame(5, $product->fresh()->stock_quantity);
    }

    public function test_role_boundaries_and_anonymous_access(): void
    {
        foreach (['dashboard', 'products.index', 'orders.index', 'customers.index', 'statistics.index'] as $route) {
            $this->get(route($route))->assertRedirect(route('login'));
        }
        $stock = User::factory()->create(['role' => 'stock_manager']);
        $this->actingAs($stock)->get(route('orders.index'))->assertForbidden();
        $this->get(route('customers.index'))->assertForbidden();
        $this->get(route('statistics.index'))->assertForbidden();
        $this->get(route('products.create'))->assertOk();
        $agent = User::factory()->create(['role' => 'agent']);
        $this->actingAs($agent)->get(route('products.create'))->assertForbidden();
        $this->get(route('statistics.index'))->assertForbidden();
    }

    public function test_order_total_cannot_overflow_database_precision(): void
    {
        [$order, $product] = $this->order();
        $product->update(['stock_quantity' => 10000, 'selling_price' => '999999.99']);
        $this->postJson(route('orders.store'), ['customer_id' => $order->customer_id, 'product_id' => $product->id, 'quantity' => 10000, 'delivery_fee' => 0])->assertUnprocessable();
        $this->assertSame(1, Order::count());
    }

    public function test_product_validation_and_update(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'stock_manager']));
        $payload = ['name' => 'Demo product', 'purchase_price' => 10, 'selling_price' => 20, 'stock_quantity' => 4];
        $this->post(route('products.store'), $payload)->assertRedirect(route('products.index'));
        $product = Product::firstOrFail();
        $this->patch(route('products.update', $product), array_replace($payload, ['stock_quantity' => -1]))->assertSessionHasErrors('stock_quantity');
        $this->patch(route('products.update', $product), array_replace($payload, ['selling_price' => '1.999']))->assertSessionHasErrors('selling_price');
        $this->patch(route('products.update', $product), array_replace($payload, ['stock_quantity' => 7]))->assertRedirect();
        $this->assertSame(7, $product->fresh()->stock_quantity);
    }

    public function test_product_delete_endpoint_respects_history_and_roles(): void
    {
        [$order, $product] = $this->order();
        $this->delete(route('products.destroy', $product))->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'stock_manager']));
        $this->delete(route('products.destroy', $product))->assertSessionHasErrors('product');
        $unused = Product::factory()->create();
        $this->delete(route('products.destroy', $unused))->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $unused->id]);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_linked_product_cannot_be_deleted(): void
    {
        [$order, $product] = $this->order();
        $this->expectException(ValidationException::class);
        $product->delete();
    }

    public function test_tracking_requires_token_and_stock_cannot_read_order(): void
    {
        [$order] = $this->order();
        $this->get(route('orders.track', [$order, 'wrong']))->assertNotFound();
        $this->get($order->tracking_url)->assertOk();
        $this->actingAs(User::factory()->create(['role' => 'stock_manager']));
        $this->get(route('orders.show', $order))->assertForbidden();
        $this->patchJson(route('orders.updateStatus', $order), ['status' => 'confirmed'])->assertForbidden();
    }

    public function test_dashboard_revenue_ignores_pending_orders(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        Order::factory()->create(['status' => 'pending', 'total_amount' => 900, 'created_at' => now()]);
        Order::factory()->create(['status' => 'delivered', 'total_amount' => 80, 'created_at' => now()]);
        $data = app(DashboardService::class)->forUser($admin);
        $this->assertEquals(80, $data['monthlyRevenue']);
        foreach (['dashboard', 'orders.index', 'orders.kanban', 'customers.index', 'statistics.index', 'products.index'] as $route) {
            $this->actingAs($admin)->get(route($route))->assertOk();
        }
    }

    public function test_new_migration_preserves_historical_lines_without_inventing_costs(): void
    {
        [$order, $product] = $this->order();
        Schema::table('order_product', fn ($table) => $table->dropColumn('unit_cost'));
        $migration = require database_path('migrations/2026_11_13_000001_add_unit_cost_to_order_product.php');
        $migration->up();
        $line = $order->products()->firstOrFail();
        $this->assertNull($line->pivot->unit_cost);
        $this->assertEquals(2, $line->pivot->quantity);
        $this->assertEquals(25, $line->pivot->unit_price);
    }

    public function test_csv_export_neutralizes_spreadsheet_formulas(): void
    {
        [$order] = $this->order();
        $order->customer->update(['name' => '=HYPERLINK("example.test")']);
        $response = $this->get(route('orders.export'))->assertOk();
        $this->assertStringContainsString("'=HYPERLINK", $response->streamedContent());
    }

    public function test_empty_statistics_and_delivery_rate(): void
    {
        $stats = app(StatisticsService::class)->forFilter('all');
        $this->assertEquals(0, $stats['totalRevenue']);
        $this->assertSame([], $stats['chartData']);
        $customer = Customer::factory()->create();
        $this->assertSame(0, $customer->delivery_rate);
        Order::factory()->create(['customer_id' => $customer->id, 'status' => 'delivered']);
        Order::factory()->create(['customer_id' => $customer->id, 'status' => 'pending']);
        $this->assertSame(50, $customer->fresh()->delivery_rate);
    }

    public function test_account_deletion_does_not_cascade_order_history(): void
    {
        [$order] = $this->order();
        $this->delete(route('profile.destroy'), ['password' => 'password'])->assertStatus(409);
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }
}
