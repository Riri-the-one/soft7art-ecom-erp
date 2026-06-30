<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'city', 'address', 'is_blacklisted'
    ];

    // Un client peut passer plusieurs commandes
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getDeliveryRateAttribute(): int
    {
        $total = $this->orders()->count();
        if ($total === 0) {
            return 0;
        }

        $delivered = $this->orders()->where('status', 'delivered')->count();

        return round(($delivered / $total) * 100, 0);
    }
}