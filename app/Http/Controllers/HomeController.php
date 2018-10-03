<?php

namespace App\Http\Controllers;

use App\Column;
use App\Key;
use App\Product;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $keys = $request->keys();

        $template = Template::findOrFail($id);
        $columns = explode(';',$request->indexexport);
        $products = $template->productsWillExport;
        $data = [];
        $dbColumns = Column::get();
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
                    $d[$column] =$keyt->key;
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

    public function test(Request $request){

    }
}
