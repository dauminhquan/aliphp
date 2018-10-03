<?php

namespace App\Http\Controllers;

use App\Column;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $columns = Schema::getColumnListing('products');
        $data = [];
        foreach ($columns as $index => $column)
        {
            $data[] = [
                "id" => $index+1,
                'name' => $column
            ];
        }
        return $data;
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
        $column = $request->column;
        try{
            Schema::table('products', function (Blueprint $table) use ($column,$request) {
                $table->string($column)->nullable();
            });
        }catch (\Exception $exception)
        {
            return response()->json(['error' => $exception->getMessage()],500);
        }
        return 'success';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $column)
    {
        try{
            Schema::table('products', function (Blueprint $table) use ($column,$request) {
                $table->renameColumn($column,$request->column);
            });
            Column::where('product_column',$column)->update(['product_column' => $request->column]);
        }catch (\Exception $exception)
        {
            return response()->json(['error' => $exception->getMessage()],500);
        }
        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($column)
    {
        try{
            Schema::table('products', function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
            Column::where('product_column',$column)->update(['product_column' => null]);
        }catch (\Exception $exception)
        {
            return response()->json(['error' => 'column'],500);
        }
        return 'success';
    }
}
