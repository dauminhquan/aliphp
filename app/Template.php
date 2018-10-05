<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = ['name','sort'];
    public function columns(){
        return $this->belongsToMany(Column::class,'template_columns');
    }
    public function products(){
        return $this->belongsToMany(Product::class,'template_products');
    }
    public function productsWillExport(){
        return $this->belongsToMany(Product::class,'template_products')->where('exported',false);
    }
    public function commonColumns(){
        return $this->belongsToMany(Column::class,'common_columns');
    }
}
