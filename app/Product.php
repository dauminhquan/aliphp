<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'product_url',
        'product_id',
        'product_star',
        'product_rating_des',
        'product_rating_seller',
        'product_rating_shipping',
        'description',
        'category_id'
    ];
    public function colors(){
        return $this->hasMany(Color::class);
    }
    public function sizes()
    {
        return $this->hasMany(Size::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
