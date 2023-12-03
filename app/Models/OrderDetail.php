<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'productQuantity'];

    /**
     * Get the order that owns the order detail.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the order detail.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($orderDetail) {
    //         $orderDetail->productQuantity = static::where('order_id', $orderDetail->order_id)->count();
    //     });
    // }

}
