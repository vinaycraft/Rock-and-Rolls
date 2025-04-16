<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrderItem;

class Dish extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'price_with_cheese',
        'image_path',
        'is_available'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'price_with_cheese' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedBasePriceAttribute()
    {
        return number_format($this->base_price, 2);
    }

    public function getFormattedPriceWithCheeseAttribute()
    {
        return number_format($this->price_with_cheese, 2);
    }
}
