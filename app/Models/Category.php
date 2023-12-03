<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // protected $primaryKey = 'categoryId';

    protected $fillable = [
        'name',
        'description',
    ];

    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($Category) {
    //         // Generate a custom ID starting with 'TRS'
    //         $Category->categoryId = 'CTG' . uniqid();
    //     });
    // }

    // Define relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }


}
