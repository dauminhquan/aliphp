<?php

namespace App\Http\Controllers;

use App\Excel;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inputs = $request->all();
        if(isset($inputs['size']))
        {
            if($inputs['size'] == -1)
            {
                $products = Product::paginate(100000);
            }
            else{
                $products = Product::paginate($inputs['size']);
            }
        }
        else {
            $products = Product::paginate(500);
        }
        return $products;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = (array)$request->all();
        $data['item_sku'] = strtoupper(Str::random(10));
        while (Product::where('item_sku',$data['item_sku'])->first() != null)
        {
            $data['item_sku'] = strtoupper(Str::random(10));
        }
        $columns = Schema::getColumnListing('products');
        $product = new Product();
            foreach ($data as $key=> $item)
            {
                if(in_array($key,$columns))
                {
                    $product->$key = $item;
                }
            }

       $product->save();
       return $product;
    }
    public function destroyMany(Request $request){
        $ids = $request->id_list;
        foreach ($ids as $id)
        {
            $product = Product::find($id);
            if($product != null)
            {
                $product->delete();
            }
        }
        return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('id',$id)->update($request->all());
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();
        Excel::where('product_id',$product->id)->delete();
        DB::table('template_products')->where('product_id',$product->id)->delete();
        return $product;
    }
    public function destroies(Request $request){
        $products = $request->products;
        foreach ($products as $product)
        {
            $_product = Product::findOrFail($product['id']);
            $_product->delete();
            Excel::where('product_id',$product['id'])->delete();
            DB::table('template_products')->where('product_id','=',$product['id'])->delete();
        }
        return $products;
    }
}
