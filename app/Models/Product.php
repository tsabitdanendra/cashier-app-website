<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // protected $primaryKey = 'productId';

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($Product) {
    //         // Generate a custom ID starting with 'TRS'
    //         $Product->productId = 'PRD' . uniqid();
    //     });
    // }
    // Define relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
