<?php

namespace App\Http\Controllers;

use App\Key;
use Illuminate\Http\Request;

class KeyController extends Controller
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
                $keys = Key::paginate(100000);
            }
            else{
                $keys = Key::paginate($inputs['size']);
            }
        }
        else {
            $keys = Key::paginate(500);
        }
        /*foreach ($products as $product)
        {
            $product->sizes = $product->sizes;
            $product->colors = $product->colors;
            $product->category = $product->category;
        }*/
        return $keys;
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
        $key = Key::create($request->all());
        return $key;
    }
    public function storeMany(Request $request)
    {
        $keys = $request->keys;
        $listError = [];
        foreach ($keys as $key)
        {
            if(Key::where('key',$key['key'])->first() != null)
            {
                $listError[] = $key;
            }
            else{
                Key::create($key);
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
        $key = Key::findOrFail($id);
        return $key;
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
        $key = Key::findOrFail($id);
        $key->delete();
        return $key;
    }
    public function destroyMany(Request $request){
        $id_list = $request->id_list;
        foreach ($id_list as $id)
        {
            $key = Key::find($id);
            $key->delete();
        }
        return $id_list;
    }
}
