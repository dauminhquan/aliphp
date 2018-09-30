<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtherController extends Controller
{
    public function getProductColumns(){
        return DB::getSchemaBuilder()->getColumnListing('products');
    }
}
