<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = ['name'];
    public function columns(){
        return $this->belongsToMany(Column::class,'template_columns');
    }
    public function products(){
        return $this->belongsToMany(Product::class,'template_products');
    }
}
