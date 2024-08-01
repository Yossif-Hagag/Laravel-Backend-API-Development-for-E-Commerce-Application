<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'quantity'];

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_product')->withPivot('quantity')->withTimestamps();
    }
    public function wishlists()
    {
        return $this->belongsToMany(Wishlist::class, 'product_wishlist')->withTimestamps();
    }
}
