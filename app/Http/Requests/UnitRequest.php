<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Unit;

class UnitRequest extends FormRequest
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
        $unit = new Unit;
        $data = [
            'rental_terms' => 'required|in:'.implode(',', array_keys($unit->terms)),
            'long_term_minimum' => 'required_without:short_term_minimum|in:'.implode(',', array_keys($unit->terms['LONG'])),
            'short_term_minimum' => 'required_without:long_term_minimum|in:'.implode(',', array_keys($unit->terms['SHORT'])),
            'long_term_rate' => 'required_without:short_term_minimum',
            'short_term_daily_rate' => 'required_without:long_term_minimum',
            'short_term_weekly_rate' => 'required_without:long_term_minimum',
            'short_term_monthly_rate' => 'required_without:long_term_minimum',
            'unit_number' => 'required',
            'unit_floor' => 'required',
            'furnishing' => 'required|in:'.implode(',' , array_keys($unit->furnishings)),
            'bedrooms' => 'required|in:'.implode(',' , range(1, 5)),
            'bathrooms' => 'required|in:'.implode(',' , range(1, 5)),
             'photos' => 'required|image|max:2048',
             'amenities' => 'array'
        ];

        return $data;
    }

     protected function formatErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }
}
