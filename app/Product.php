<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'item_name',
        'item_sku',
        'standard_price',
        'product_description',
        'main_image_url',
        'swatch_image',
        'colors',
        'sizes',
        'other_images',
        'branch_aliexpress',
        'url_aliexpress',
        'specifics',
        'key_works',
        'aliexpress_product_id'
    ];
}
