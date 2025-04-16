<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Order;
use App\Models\Dish;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'dish_id',
        'quantity',
        'has_cheese',
        'price',
        'dish_name'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'has_cheese' => 'boolean',
        'quantity' => 'integer'
    ];

    protected $appends = ['subtotal'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class)->withTrashed();
    }

    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    public function getDishNameAttribute()
    {
        return $this->attributes['dish_name'] ?? ($this->dish ? $this->dish->name : 'Deleted Dish');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            if (!$item->price) {
                $dish = Dish::withTrashed()->find($item->dish_id);
                if ($dish) {
                    $item->price = $item->has_cheese ? $dish->price_with_cheese : $dish->base_price;
                    $item->dish_name = $dish->name;
                }
            }
        });

        static::saved(function ($item) {
            if ($item->order) {
                $item->order->calculateTotal();
            }
        });

        static::deleted(function ($item) {
            if ($item->order) {
                $item->order->calculateTotal();
            }
        });
    }
}
