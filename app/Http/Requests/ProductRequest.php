<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod())
        {
            case 'POST':
                return [
                    'product_id' => 'required| unique:products,product_id',
                    'product_name' => 'required',
                    'product_url' => 'required',
                    'colors' => 'required|array',
                    'images' => 'required|array',
                    'sizes' => 'required|array',
                    'description' => 'required',
                    'prices' => 'required|array'
                ];
            default:
                return [];
        }
    }
}
