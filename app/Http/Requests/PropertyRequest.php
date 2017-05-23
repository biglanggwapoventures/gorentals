<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class PropertyRequest extends FormRequest
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
        $data = [
            'address' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'building_name' => 'required',
            'policies' => 'required|min:1',
            'policies.*' => 'required|exists:property_policies,id',
            'permit_number' => 'required',
            'property_type' => 'required|in:APARTMENT,BOARDING_HOUSE,DORMITORY',
            'photos.primary.0' => 'required|image|max:2048',
            'photos.primary.1' => 'image:max:2048',
            'photos.interior.0' => 'image:max:2048',
            'photos.interior.1' => 'image:max:2048',
            'photos.bedrooms.0' => 'image:max:2048',
            'photos.bedrooms.1' => 'image:max:2048',
            'photos.bathrooms.0' => 'image:max:2048',
            'photos.bathrooms.1' => 'image:max:2048',
            'photos.amenities.0' => 'image:max:2048',
            'photos.amenities.1' => 'image:max:2048',
        ];

        return $data;
    }

    protected function formatErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }



}
