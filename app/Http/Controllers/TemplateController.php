<?php

namespace App\Http\Controllers;

use App\CommonColumn;
use App\Excel;
use App\Product;
use App\Template;
use App\TemplateColumn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            foreach ($template->products as $product)
            {
                $dt = DB::table('template_products')->where('product_id',$product->id)->where('template_id',$template->id)->first();
                $product->exported = $dt->exported == 0? 'Chưa xuất Excel' : 'Đã xuất Excel';
            }
            return $template;
    }
    public function product($id,$product_id){
        $template = Template::findOrFail($id);
        $columns = $template->columns;
        $product = Product::findOrFail($product_id);
        foreach ($columns as $column)
        {
            $data = Excel::where('column_id',$column->id)->where('product_id',$product_id)->where('template_id',$id)->first();
            if($data != null)
            {
                $n = $column->name;
                $product->$n = $data->value;
            }
        }
        return $product;
    }
    public function updateProduct(Request$request,$id,$product_id){
        $template = Template::findOrFail($id);
        $columns = $template->columns;
        $product = Product::findOrFail($product_id);
        foreach ($columns as $column)
        {
            $excel = Excel::where('column_id',$column->id)->where('product_id',$product_id)->where('template_id',$id)->first();
            $n = $column->name;
            $excel->value = $request->$n;
            $excel->update();
        }
        return $product;
    }
    public function storeProduct(Request $request)
    {
        $template = Template::findOrFail($request->template_id);
        $columns = $template->columns;
        $ids = $request->products_id;
        foreach ($ids as $i)
        {
            $product = Product::findOrFail($i);
            $template->products()->attach($i);
            foreach ($columns as $column)
            {
                $excel = new Excel();
                $excel->template_id = $template->id;
                $excel->product_id = $i;
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
        $template->sort = $request->sort;
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
        $template->sort = $request->sort;
        $oldColumns = $template->columns;
        $idOldColumns = [];
        $newColumns = $request->columns;
        $products = $template->products;
        foreach ($oldColumns as $oldColumn)
        {
            $idOldColumns[] = $oldColumn->id;
        }
        $remove = [];
        foreach ($idOldColumns as $idOldColumn)
        {
            if(!in_array($idOldColumn,$newColumns))
            {
                $remove[] = $idOldColumn;
            }
        }
        $add = [];
        foreach ($newColumns as $newColumn)
        {
            if(!in_array($newColumn,$idOldColumns))
            {
                $add[] = $newColumn;
            }
        }
        foreach ($remove as $item)
        {
            Excel::where('template_id',$id)->where('column_id',$item)->delete();
        }
        foreach ($add as $item)
        {
            foreach ($products as $product)
            {
                $excel = new Excel();
                $excel->template_id = $template->id;
                $excel->column_id = $item;
                $excel->value = null;
                $excel->product_id = $product->id;
                $excel->save();
            }
        }
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
    public function saveCommonColumns(Request $request,$id)
    {
        $template = Template::findOrFail($id);
        $cls = $request->common;
        $template->commonColumns()->detach();
        foreach ($cls as $cl)
        {
            $cl = (Object)$cl;
            $template->commonColumns()->attach($cl->key,['value' => $cl->value]);
        }
        return $template;
    }
    public function getCommonColumns($id)
    {
        $template = Template::findOrFail($id);
        return CommonColumn::where('template_id',$id)->get();
    }
    public function changeStatusProduct($id,$productId){
        $template = Template::findOrFail($id);
        $p = DB::table('template_products')->where('template_id','=',$id)->where('product_id','=',$productId)->first();
        if($p != null)
        {
            if($p->exported == 1)
            {
                DB::table('template_products')->where('template_id','=',$id)->where('product_id','=',$productId)->update(['exported' => 0]);
                $columns = $template->columns;
                $product = Product::findOrFail($productId);
                foreach ($columns as $column)
                {
                    $excel = new Excel();
                    $excel->template_id = $template->id;
                    $excel->product_id = $productId;
                    $excel->column_id = $column->id;
                    $cl = $column->product_column;
                    $excel->value = $product->$cl;
                    $excel->save();
                }
            }
            else{
                DB::table('template_products')->where('template_id','=',$id)->where('product_id','=',$productId)->update(['exported' => 1]);
                Excel::where('product_id', $productId)->where('template_id',$id)->delete();
            }
        }
        return $template;

    }
}
