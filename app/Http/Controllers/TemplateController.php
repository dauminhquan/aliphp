<?php

namespace App\Http\Controllers;

use App\Excel;
use App\Product;
use App\Template;
use App\TemplateColumn;
use Illuminate\Http\Request;

class TemplateController extends Controller
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
                $templates = Template::paginate(100000);
            }
            else{
                $templates = Template::paginate($inputs['size']);
            }
        }
        else {
            $templates = Template::paginate(500);
        }
        return $templates;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function products($id)
    {
            $template = Template::findOrFail($id);
            $template->products = $template->products;
            return $template;
    }
    public function storeProduct(Request $request)
    {
        $template = Template::findOrFail($request->template_id);
        $ids = $request->products_id;
        foreach ($ids as $i)
        {
            $product = Product::findOrFail($i);
            $template->products()->attach($product->id);
            $columns = $template->columns;
            foreach ($columns as $column)
            {
                $excel = new Excel();
                $excel->template_id = $template->id;
                $excel->product_id = $product->id;
                $excel->column_id = $column->id;
                $cl = $column->product_column;
                $excel->value = $product->$cl;
                $excel->save();
            }
        }
        return $request->all();
    }
    public function destroyProduct(Request $request)
    {
        $template = Template::findOrFail($request->template_id);
        $product = Product::findOrFail($request->product_id);
        $template->products()->detach($product->id);
        Excel::where('product_id',$product->id)->where('template_id',$template->id)->delete();
        return $request->all();
    }
    public function destroyManyProduct(Request $request)
    {
        $template = Template::findOrFail($request->template_id);
        $ids = $request->id_list;
        foreach ($ids as $id)
        {
            $product = Product::find($id);
            if($product!= null)
            {
                $template->products()->detach($product->id);
                Excel::where('product_id',$product->id)->where('template_id',$template->id)->delete();
            }
        }
        return $request->all();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $template = new Template;
        $template->name = $request->template_name;
        $template->save();

        try
        {
            if($request->has('columns'))
            {
                $columns = $request->columns;
                foreach ($columns as $column)
                {
                    $template->columns()->attach($column);
                }
            }
        }catch(\Exception $exception) {
            $template->delete();
            return response()->json($exception->getMessage(),500);
        }
        $template->columns = $template->columns;
        return $template;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $template = Template::findOrFail($id);
        $columns = $template->columns;
        foreach ($columns as $column)
        {
            $template_column = TemplateColumn::where('template_id',$template->id)->where('column_id',$column->id)->first();
            $column->product_column = $template_column == null ? null : $template_column->product_column;
        }
        $template->columns = $columns;
        return $template;
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
        $template = Template::findOrFail($id);
        $template->name = $request->name;
        $template->columns()->sync($request->columns);
        $template->update();
        return $template;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $template = Template::findOrFail($id);
        $template->columns()->sync([]);
        Excel::where('template_id',$id)->delete();
        $template->delete();
        return $template;
    }
}
