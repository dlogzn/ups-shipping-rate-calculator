<?php

namespace App\Http\Requests\AccountPanel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShippingRateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        $rules['country_id'] = [
            'required',
            'numeric'
        ];
        $rules['service_code'] = [
            'required',
            'numeric'
        ];
        $rules['origin_city'] = [
            'nullable',
            'string',
            'max:255'
        ];
        $rules['origin_zip_code'] = [
            'required',
            'string',
            'min:3',
            'max:10'
        ];
        $rules['destination_city'] = [
            'nullable',
            'string',
            'max:255'
        ];
        $rules['destination_zip_code'] = [
            'required',
            'string',
            'min:3',
            'max:10'
        ];
        $rules['destination_type'] = [
            'required',
            Rule::in(['Residential', 'Commercial'])
        ];
        $rules['shipping_date'] = [
            'nullable',
            'date_format:Y-m-d',
            'after_or_equal:' . date('Y-m-d')
        ];

        $rules['number_of_boxes'] = [
            'required',
            'numeric',
            'gt:0'
        ];

        $rules['package_length.*'] = [
            'required',
            'numeric'
        ];
        $rules['package_width.*'] = [
            'required',
            'numeric'
        ];
        $rules['package_height.*'] = [
            'required',
            'numeric'
        ];
        $rules['package_weight.*'] = [
            'required',
            'numeric'
        ];
        return $rules;
    }
}
