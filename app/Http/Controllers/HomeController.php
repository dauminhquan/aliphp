<?php

namespace App\Http\Controllers;

use App\Column;
use App\Key;
use App\Product;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel as ExcelModel;
class HomeController extends Controller
{
    public function index()
    {
        $products = Product::paginate(20);
        return view('index',[
            'products' => $products
        ]);
    }
    public function columns(){
        return view('columns');
    }

    public function templates(){
        return view('templates');
    }
    public function template($id)
    {
        $template = Template::findOrFail($id);
        return view('template',['id' => $id]);
    }
    public function keys(){
        return view('keys');
    }
    public function exportExcel(Request $request,$id){
        ini_set('max_execution_time', 300);
        $keys = $request->keys();
        $template = Template::findOrFail($id);
        $columns = explode(';',$template->sort);
        $products = $template->productsWillExport;
        $data = [];
        $dbColumns = Column::get();
        if($request->has('variant'))
        {
            foreach ($products as $product)
            {
                $ps =  ExcelModel::where('template_id',$template->id)->where('product_id',$product->id)->get();
                if($this->checkVariant($product))
                {
                    $keyt = Key::first();
//                dd($columns);
                    $this->setProduct($data,$product,$columns,$ps,$request,$keys,$dbColumns,$keyt);
                    continue;
                }
                //check xem có cha con k
                else{
                    foreach ($columns as $column)
                    {
                        if(in_array($column,$keys))
                        {
                            $key = $column;
                            $d[$key] = $request->$key;
                        }
                        else if($column == 'external_product_id'){
                            $keyt = Key::first();
                            $d[$column] = $keyt->key;
                            $keyt->delete();
                        }
                        else{
                            try{
                                foreach ($dbColumns as $cl)
                                {
                                    if($cl->name == $column)
                                    {
                                        $column = $cl;
                                        break;
                                    }
                                }
                                foreach ($ps as $p)
                                {
                                    if($p->column_id == $column->id)
                                    {
                                        $d[$column->name] = $p->value;
                                        break;
                                    }
                                }
                                /*$p =  ExcelModel::where('template_id',$template->id)->where('product_id',$product->id)->where('column_id',$column->id)->first();*/

                            }catch (\Exception $exception){
                                dd($template->id,$product->id,$column->id);
                            }
                        }
                    }
                    $data[] = $d;
                }

            }
        }
        else{
            foreach ($products as $product)
            {
                $ps =  ExcelModel::where('template_id',$template->id)->where('product_id',$product->id)->get();
                foreach ($columns as $column)
                {
                    if(in_array($column,$keys))
                    {
                        $key = $column;
                        $d[$key] = $request->$key;
                    }
                    else if($column == 'external_product_id'){
                        $keyt = Key::first();
                        $d[$column] = $keyt->key;
                        $keyt->delete();
                    }
                    else{
                        try{
                            foreach ($dbColumns as $cl)
                            {
                                if($cl->name == $column)
                                {
                                    $column = $cl;
                                    break;
                                }
                            }
                            foreach ($ps as $p)
                            {
                                if($p->column_id == $column->id)
                                {
                                    $d[$column->name] = $p->value;
                                    break;
                                }
                            }
                            /*$p =  ExcelModel::where('template_id',$template->id)->where('product_id',$product->id)->where('column_id',$column->id)->first();*/

                        }catch (\Exception $exception){
                            dd($template->id,$product->id,$column->id);
                        }
                    }
                }
                $data[] = $d;

            }
        }

//        dd($data);
        DB::table('template_products')->where('template_id','=',$id)->where('exported','=',0)->update(['exported' => 1]);
        ExcelModel::where('template_id',$id)->delete();
       return Excel::create('Filename', function($excel) use ($data) {

            // Set the title
            $excel->setTitle('Our new awesome title');

            // Chain the setters
            $excel->setCreator('Maatwebsite')
                ->setCompany('Maatwebsite');

            // Call them separately
            $excel->setDescription('A demonstration to change the file properties');
            $excel->sheet('products', function($sheet) use ($data ){

                $sheet->fromArray($data);

            });
        })->download('xlsx');
    }
    public function productColumns(){
        return view('product_columns');
    }
    private function filter()
    {
        $data = [];
        foreach ($this->default_columns_name as $cl)
        {
            $data[$cl] = 'required';
        }
        return $data;
    }

    public function replacePassword(Request $request){

            $passOld = $request->oldPassword;
            if(Hash::check($passOld,Auth::user()->password))
            {
                $user = Auth::user();
                $user->password = Hash::make($request->newPassword);
                $user->update();
                return response()->json(['message' => 'success'],200);
            }
            return response()->json(['message' => 'err'],403);
    }
    private function checkVariant(Product $product)
    {
        if($product->colors != null)
        {
            $colors = explode(';',$product->colors);
            if(count($colors) > 1)
            {
               return true;
            }
        }
        return false;
    }
    private function setProduct(&$data,Product $product,$columns,$ps,$request,$keys,$dbColumns,$keyt)
    {

        $validate = ['item_sku','item_name','variation_theme','parent_child'];
        $other_images = ['other_image_url1','other_image_url2','other_image_url3'];
        $sizes = explode(';',$product->sizes);
        $colors = $product->colors;
        $image_colors = $product->images_colors;
        $image_colors = explode(';',$image_colors);
        foreach ($columns as $column)
        {
            if(in_array($column,$keys) && in_array($column,$validate))
            {
                $key = $column;
                $d[$key] = $request->$key;
            }
            else if($column == 'external_product_id'){
                $d[$column] = $keyt->key;
                $keyt->delete();
            }
            else{
                try{
                    foreach ($dbColumns as $cl)
                    {
                        if($cl->name == $column && in_array($cl->name,$validate))
                        {
                            $column = $cl;
                            break;
                        }
                    }
                    if(gettype($column) == 'string')
                    {
                        $d[$column] = null;
                        continue;
                    }
                    if($column->name == 'variation_theme')
                    {
                        $d[$column->name] = 'colorsize';
                        continue;
                    }
                    if($column->name == 'parent_child')
                    {
                        $d[$column->name] = 'Parent';
                        continue;
                    }
                    foreach ($ps as $p)
                    {
                        if($p->column_id == $column->id)
                        {
                            $d[$column->name] = $p->value;
                            break;
                        }
                    }
                    /*$p =  ExcelModel::where('template_id',$template->id)->where('product_id',$product->id)->where('column_id',$column->id)->first();*/

                }catch (\Exception $exception){
                    dd($exception);
                }
            }
        }
        $data[] = $d;
        $colors = explode(';',$colors);
        unset($colors[count($colors) - 1]);
        foreach ($colors as $index => $color)
        {
            if(count($sizes) > 1 )
            {
                foreach ($columns as $column)
                {
                    /*if(in_array($column,$other_images))
                    {
                        $d1[$column] = null;
                    }
                    else */

                    if(in_array($column,$keys))
                    {
                        $key = $column;
                        $d1[$key] = $request->$key;
                    }
                    else if($column == 'main_image_url'){

                        $d1[$column] = $image_colors[$index];
                    }
                    else if($column == 'external_product_id'){
                        $k = Key::first();
                        $d1[$column] = $k->key;
                        $k->delete();
                    }
                    else if($column == 'parent_sku')
                    {
                        $d1[$column] = $d["item_sku"];
                    }
                    else if($column == 'relationship_type')
                    {
                        $d1[$column] = 'Variation';
                    }
                    else if($column == 'item_sku')
                    {
                        $sku = strtoupper(Str::random(10));
                        while (Product::where('item_sku',$sku)->first() != null)
                        {
                            $sku = strtoupper(Str::random(10));
                        }
                        $d1[$column] = $sku;
                    }
                    else if($column == 'parent_child')
                    {
                        $d1[$column] = 'Parent';
                    }
                    else if($column == 'variation_theme')
                    {
                        $d1[$column] = 'colorsize';
                        continue;
                    }
                    else if($column == 'color_name'){
                        $d1[$column] = $color;
                    }
                    else{

                        try{
                            foreach ($dbColumns as $cl)
                            {
                                if($cl->name == $column)
                                {
                                    $column = $cl;
                                    break;
                                }
                            }

                            foreach ($ps as $p)
                            {
                                if($p->column_id == $column->id)
                                {
                                    $d1[$column->name] = $p->value;
                                    break;
                                }
                            }
                            /*$p =  ExcelModel::where('template_id',$template->id)->where('product_id',$product->id)->where('column_id',$column->id)->first();*/

                        }catch (\Exception $exception){
                            dd(456);
                        }
                    }
                }

                $data[] = $d1;

                $this->setSize($data,$columns,$other_images,$keys,$request,$image_colors[$index],$sizes,$d1,$dbColumns,$ps);
            }
            else{
                foreach ($columns as $column)
                {
                    if(in_array($column,$other_images))
                    {
                        $d1[$column] = null;
                    }
                    else if(in_array($column,$keys))
                    {
                        $key = $column;
                        $d1[$key] = $request->$key;
                    }
                    else if($column == 'main_image_url'){
                        $d1[$column] = $image_colors[$index];
                    }
                    else if($column == 'external_product_id'){
                        $k = Key::first();
                        $d1[$column] = $k->key;
                        $k->delete();
                    }
                    else if($column == 'parent_sku')
                    {
                        $d1[$column] = $product->item_sku;
                    }
                    else if($column == 'relationship_type')
                    {
                        $d1[$column] = 'Variation';
                    }
                    else if($column == 'item_sku')
                    {
                        $sku = strtoupper(Str::random(10));
                        while (Product::where('item_sku',$sku)->first() != null)
                        {
                            $sku = strtoupper(Str::random(10));
                        }
                        $d1[$column] = $sku;
                    }
                    else if($column == 'parent_child')
                    {
                        $d1[$column] = 'Child';
                    }
                    else if($column == 'color_name'){
                        $d1[$column] = $color;
                    }
                    else{
                        try{
                            foreach ($dbColumns as $cl)
                            {
                                if($cl->name == $column)
                                {
                                    $column = $cl;
                                    break;
                                }
                            }
                            foreach ($ps as $p)
                            {
                                if($p->column_id == $column->id)
                                {
                                    $d1[$column->name] = $p->value;
                                    break;
                                }
                            }
                            /*$p =  ExcelModel::where('template_id',$template->id)->where('product_id',$product->id)->where('column_id',$column->id)->first();*/

                        }catch (\Exception $exception){
                            dd(456);
                        }
                    }
                }
            }



            $data[] = $d1;
        }


    }
    private function setSize(&$data,$columns,$other_images,$keys,$request,$image_colors_index,$sizes,$product,$dbColumns,$ps)
    {
        foreach ($sizes as $index => $size)
        {
            foreach ($columns as $column)
            {
                if(in_array($column,$other_images))
                {
                    $d1[$column] = null;
                }
                else if(in_array($column,$keys))
                {
                    $key = $column;
                    $d1[$key] = $request->$key;
                }
                else if($column == 'main_image_url'){
                    $d1[$column] = $image_colors_index;
                }
                else if($column == 'external_product_id'){
                    $k = Key::first();
                    $d1[$column] = $k->key;
                    $k->delete();
                }
                else if($column == 'parent_sku')
                {
                    $d1[$column] = $product['item_sku'];
                }
                else if($column == 'relationship_type')
                {
                    $d1[$column] = 'Variation';
                }
                else if($column == 'item_sku')
                {
                    $sku = strtoupper(Str::random(10));
                    while (Product::where('item_sku',$sku)->first() != null)
                    {
                        $sku = strtoupper(Str::random(10));
                    }
                    $d1[$column] = $sku;
                }
                else if($column == 'parent_child')
                {
                    $d1[$column] = 'Child';
                }
                else if($column == 'size_name'){
                    $d1[$column] = $size;
                }
                else{

                    try{

                        $d1[$column] = $product[$column];
                       /* foreach ($dbColumns as $cl)
                        {
                            if($cl->name == $column)
                            {
                                $column = $cl;
                                break;
                            }
                        }
                        foreach ($ps as $p)
                        {
                            if($p->column_id == $column->id)
                            {
                                $d1[$column->name] = $p->value;
                                break;
                            }
                        }*/
                        /*$p =  ExcelModel::where('template_id',$template->id)->where('product_id',$product->id)->where('column_id',$column->id)->first();*/

                    }catch (\Exception $exception){
                        dd($exception);
                    }
                }
            }
            $data[] = $d1;
        }

    }
}
