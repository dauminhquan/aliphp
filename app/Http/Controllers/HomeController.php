<?php

namespace App\Http\Controllers;

use App\Product;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    private $columns_name = ["feed_product_type","item_sku","brand_name","item_name","item_type","standard_price","quantity","merchant_shipping_group_name","main_image_url","swatch_image_url","other_image_url1","other_image_url2","other_image_url3","parent_child","parent_sku","relationship_type","variation_theme","update_delete","product_description","manufacturer","external_product_id","part_number","external_product_id_type","model","closure_type","bullet_point1","bullet_point2","bullet_point3","bullet_point4","bullet_point5","target_audience_base","catalog_number","specific_uses_keywords1","specific_uses_keywords2","specific_uses_keywords3","specific_uses_keywords4","specific_uses_keywords5","target_audience_keywords1","target_audience_keywords2","target_audience_keywords3","thesaurus_attribute_keywords1","thesaurus_attribute_keywords2","thesaurus_attribute_keywords3","thesaurus_attribute_keywords4","thesaurus_subject_keywords1","thesaurus_subject_keywords2","thesaurus_subject_keywords3","generic_keywords1","generic_keywords2","generic_keywords3","generic_keywords4","generic_keywords5","platinum_keywords1","platinum_keywords2","platinum_keywords3","platinum_keywords4","platinum_keywords5","number_of_sets","scent_name","color_name","color_map","size_name","material_type","size_map","length_range","width_range","website_shipping_weight","website_shipping_weight_unit_of_measure","item_display_length","item_display_width","item_display_depth","item_display_height","item_display_diameter","display_dimensions_unit_of_measure","item_display_weight","item_display_weight_unit_of_measure","volume_capacity_name","volume_capacity_name_unit_of_measure","item_height","item_length","item_width","item_dimensions_unit_of_measure","fulfillment_center_id","package_height","package_width","package_length","package_dimensions_unit_of_measure","package_weight","package_weight_unit_of_measure","energy_efficiency_image_url","cpsia_cautionary_statement","cpsia_cautionary_description","fabric_type","import_designation","legal_compliance_certification_metadata","legal_compliance_certification_expiration_date","item_volume","item_volume_unit_of_measure","specific_uses_for_product","country_string","country_of_origin","legal_disclaimer_description","are_batteries_included","item_weight","batteries_required","battery_type1","battery_type2","battery_type3","item_weight_unit_of_measure","number_of_batteries1","number_of_batteries2","number_of_batteries3","lithium_battery_energy_content","lithium_battery_packaging","lithium_battery_weight","number_of_lithium_ion_cells","number_of_lithium_metal_cells","battery_cell_composition","battery_weight","battery_weight_unit_of_measure","lithium_battery_energy_content_unit_of_measure","lithium_battery_weight_unit_of_measure","supplier_declared_dg_hz_regulation1","supplier_declared_dg_hz_regulation2","supplier_declared_dg_hz_regulation3","supplier_declared_dg_hz_regulation4","supplier_declared_dg_hz_regulation5","hazmat_united_nations_regulatory_id","safety_data_sheet_url","lighting_facts_image_url","flash_point","external_testing_certification1","external_testing_certification2","external_testing_certification3","external_testing_certification4","external_testing_certification5","external_testing_certification6","ghs_classification_class1","ghs_classification_class2","ghs_classification_class3","california_proposition_65_compliance_type","california_proposition_65_chemical_names1","california_proposition_65_chemical_names2","california_proposition_65_chemical_names3","california_proposition_65_chemical_names4","california_proposition_65_chemical_names5","list_price","map_price","product_site_launch_date","merchant_release_date","condition_type","restock_date","fulfillment_latency","condition_note","product_tax_code","sale_price","sale_from_date","sale_end_date","item_package_quantity","max_aggregate_ship_quantity","offering_can_be_gift_messaged","offering_can_be_giftwrapped","is_discontinued_by_manufacturer","missing_keyset_reason","max_order_quantity","number_of_items","offering_start_date","offering_end_date","business_price","quantity_price_type","quantity_lower_bound1","quantity_price1","quantity_lower_bound2","quantity_price2","quantity_lower_bound3","quantity_price3","quantity_lower_bound4","quantity_price4","quantity_lower_bound5","quantity_price5","national_stock_number","unspsc_code","pricing_action"];
    private $default_columns_name = ["feed_product_type","item_sku","brand_name","item_type","quantity","merchant_shipping_group_name","part_number"];
    private $ali_columns_name = ["item_name","standard_price","product_description"];
    private $ali_columns_image= ["main_image_url","swatch_image_url","other_image_url1","other_image_url2","other_image_url3"];
    private $ali_bullet_point = ["bullet_point1","bullet_point2","bullet_point3","bullet_point4","bullet_point5"];
    private $ali_key_search = ["generic_keywords1","generic_keywords2","generic_keywords3","generic_keywords4","generic_keywords5"];
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

    public function exportExcel(Request $request){
        $validator = Validator::make($request->all(),$this->filter());
        if($validator->fails())
        {
            return response()->json($validator->failed(),406);
        }
        $products = Product::get();
        foreach ($products as $product)
        {
            $product->colors = $product->colors;
            $product->sizes = $product->sizes;
            $product->images = $product->images;
        }

        $data = [];

        foreach ($products as $product)
        {
            $item = [];
            foreach ($this->columns_name as $column)
            {
                $item[$column] = null;

                if(in_array($column,$this->default_columns_name))
                {
                    $item[$column] = $request->$column;
                }
                else{
                    if(in_array($column,$this->ali_columns_name))
                    {
                        if($column == "item_name")
                        {
                            $item[$column]= $product->product_name;
                        }
                        if($column == "standard_price")
                        {
                            $item[$column]= null;
                        }
                        if($column == "product_description")
                        {
                            $item[$column]= $product->product_description;
                        }

                    }
                    else if(in_array($column,$this->ali_columns_image))
                    {
                        if($column == "main_image_url")
                        {
                                foreach ($product->images as $image)
                                {
                                    if($image->type == 1)
                                    {
                                        $item[$column] = $image->image_url;
                                        break;
                                    }
                                }
                        }else{
                            foreach ($product->images as $image)
                            {
                                if($image->type != 1 && $image->setup == null)
                                {
                                    $image->setup = 1;
                                    $item[$column] = $image->image_url;
                                    break;
                                }
                            }
                        }
                    }
                    else if(in_array($column,$this->ali_bullet_point))
                    {
                        $item[$column] = null;
                    }
                    else if(in_array($column,$this->ali_key_search))
                    {
                        $item[$column] = null;
                    }
                    else{
                        $item[$column] = null;
                    }
                }
            }
            $data[] = $item;
        }
//        dd($data);
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
    private function filter()
    {
        $data = [];
        foreach ($this->default_columns_name as $cl)
        {
            $data[$cl] = 'required';
        }
        return $data;
    }
}
