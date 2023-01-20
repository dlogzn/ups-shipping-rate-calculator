<?php

namespace App\Http\Requests\AccountPanel;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShippingRateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [];
        $rules['origin_country_id'] = [
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
        $rules['destination_country_id'] = [
            'required',
            'numeric'
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
        if ($this->origin_country_id && $this->destination_country_id) {
            $shipFromCountry = Country::where('id', $this->origin_country_id)->first();
            $shipToCountry = Country::where('id', $this->destination_country_id)->first();
            if (($shipFromCountry->code === 'US' && $shipToCountry->code === 'CA') || ($shipFromCountry->code === 'CA' && $shipToCountry->code === 'US')) {
                $rules['monetary_value'] = [
                    'required',
                    'numeric'
                ];
            }
        }
        return $rules;
    }
}
