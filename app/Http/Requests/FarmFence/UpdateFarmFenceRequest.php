<?php

namespace App\Http\Requests\FarmFence;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFarmFenceRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation()
    {
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'farm_id' => 'required|exists:farms,id',
            'longitude' => 'required|max:250',
            'latitude' => 'required',
        ];

        return $rules;

    }

}
