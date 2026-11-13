# Architecture and business decisions

The application uses Laravel 12 MVC, Blade/Tailwind/Alpine and Breeze session authentication. Routes apply authentication and role middleware; policies control product management, CRM access and order operations. Sales agents deliberately share the sales workspace. Inventory employees cannot access orders or CRM, including direct URLs.

## Data model

- User has many orders and activity logs. Roles: super_admin, agent, stock_manager.
- Customer has many orders; email is unique; phone, address and city are nullable.
- Order belongs to Customer and User, has many activity logs and belongs to many Products.
- order_product stores quantity, unit_price and nullable unit_cost. Prices/costs use decimal database columns.
- OrderActivityLog records actor, previous status and new status.

## Inventory consistency

OrderStatusService opens a transaction, reloads and locks the order, handles same-state requests as no-ops and validates the transition graph. OrderObserver validates updates, locks products in ID order, checks stock and adjusts quantities before the status write. Activity logs are written in the same transaction. Exceptions roll back all movements and status changes. Mail is sent afterward; mail failure is logged without reversing committed inventory.

Use the service for every application status update. Bulk query-builder updates bypass Eloquent observers and are not a supported inventory API. SQLite serializes writes differently from MySQL; real row-lock concurrency remains to be tested on MySQL.

Confirmation reserves inventory. Pending cancellation has no stock effect. Confirmed cancellation and shipped/delivered returns restore stock. Terminal orders cannot reopen. Repeating a request cannot repeat its movement. Pending orders do not reserve stock, so confirmation may legitimately fail after another sale.

## Financial definitions

Delivered revenue includes billed shipping. Product margin excludes shipping: sum(quantity * (unit_price - unit_cost)). No separate actual shipping expense or operating-cost ledger exists, so the UI does not claim accounting net profit. New order lines snapshot purchase cost. Existing lines remain null and fall back to current purchase cost; historical figures are estimates rather than invented snapshots. Periods use order creation dates.

## Historical compatibility

The added migration only adds a nullable decimal column. Existing rows and amounts remain unchanged; a regression test applies it over historical lines. Existing migrations are preserved. Old nullable-column rollback migrations can fail if later data contains nulls: do not roll back a business database blindly. Product deletion is blocked when linked to orders. Account deletion is blocked when linked to order or audit history, preventing existing cascade constraints from erasing it through the profile endpoint.

## Reporting and scope

DashboardService and StatisticsService provide reporting data. CRM delivery rate retains delivered/all orders semantics. Eager loading prevents the obvious per-row relationship queries, but several reports still materialize collections and are intended for portfolio-scale datasets. The duplicate-order endpoint is advisory. The application has no payment integration, tax accounting or tenant isolation.
