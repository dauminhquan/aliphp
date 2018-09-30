<?php

namespace App\Http\Controllers;

use App\Column;
use App\Excel;
use App\Product;
use Illuminate\Http\Request;

class ColumnController extends Controller
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
                $colums = Column::paginate(100000);
            }
            else{
                $colums = Column::paginate($inputs['size']);
            }
        }
        else {
            $colums = Column::paginate(500);
        }
        /*foreach ($products as $product)
        {
            $product->sizes = $product->sizes;
            $product->colors = $product->colors;
            $product->category = $product->category;
        }*/
        return $colums;
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
        $column = Column::create($request->all());
        return $column;
    }
    public function storeMany(Request $request)
    {
        $columns = $request->columns;
        $listError = [];
        foreach ($columns as $column)
        {
            if(Column::where('name',$column['name'])->first() != null)
            {
                $listError[] = $column;
            }
            else{
                Column::create($column);
            }
        }

        return [
            'list_err' => $listError
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $column = Column::findOrFail($id);
        return $column;
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
        $column = Column::findOrFail($id);
        $column->update($request->all());
        $excels = Excel::where('column_id',$column->id)->get();
        if(count($excels) > 0)
        {
            foreach ($excels as $excel)
            {
                $product = Product::find($excel->product_id);
                if($product != null)
                {
                    $v = $column->product_column;
                    $excel->value = $product->$v;
                    $excel->update();
                }
                else{
                    $excel->delete();
                }
            }
        }
        return $column;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $column = Column::findOrFail($id);
        $column->delete();
        return $column;
    }
    public function destroyMany(Request $request){
        $id_list = $request->id_list;
        foreach ($id_list as $id)
        {
            $column = Column::find($id);
            $column->delete();
        }
        return $id_list;
    }
}
