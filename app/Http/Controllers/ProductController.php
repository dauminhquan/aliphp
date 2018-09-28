<?php

namespace App\Http\Controllers;

use App\Category;
use App\Color;
use App\Http\Requests\ProductRequest;
use App\Image;
use App\Price;
use App\Product;
use App\Size;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        foreach ($products as $product)
        {
            $product->sizes = $product->sizes;
            $product->colors = $product->colors;
            $product->category = $product->category;
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

        try{
            $data = $request->all();

            if($request->has('category'))
            {
                $category = Category::where(function($query) use($request) {
                    $query->where(DB::raw('LOWER(name)'),'like',strtolower($request->category));
                    $query->orWhere('id',$request->category);
                })->first();
                if($category == null)
                {
                    $category = new Category();
                    $category->name = $request->category;
                    $category->save();
                }
                $data['category_id'] = $category->id;
            }

            $product = Product::create($data);
            $colors = $request->colors;
            $sizes = $request->sizes;
            $images = $request->images;
            $prices = $request->prices;
            if($colors != null)
            {
                foreach ($colors as $color)
                {
                    $color = (Object)$color;
                    (new Color([
                        'product_id' => $product->id,
                        'name' => $color->name,
                        'url_image' => $color->url_image
                    ]))->save();
                }
            }
            if($sizes != null)
            {
                foreach ($sizes as $size)
                {
                    $size = (Object)$size;
                    (new Size([
                        'product_id' => $product->id,
                        'name' => $size->name
                    ]))->save();
                }
            }
            if($images != null)
            {
                foreach ($images as $image)
                {
                    $image = (Object)$image;
                    (new Image([
                        'product_id' => $product->id,
                        'image_url' => $image->image_url,
                        'type' => $image->type
                    ]))->save();
                }
            }
            if($prices != null)
            {
                foreach ($prices as $price)
                {
                    $price = (Object)$price;
                    (new Price([
                        'product_id' => $product->id,
                        'size' => $price->sizeName,
                        'color' => $price->colorName,
                        'price' => $price->price
                    ]))->save();
                }
            }

            return $product;
        }catch (\Exception $exception)
        {
            return response()->json([
                'err' => $exception->getMessage()
            ],500);
        }
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
        $product->sizes = $product->sizes;
        $product->colors = $product->colors;
        $product->category = $product->category;
        $product->images = $product->images;
        $product->prices = $product->prices;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
