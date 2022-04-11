<?php

namespace App\Http\Requests\Farms;

use App\Enums\FarmStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFarmRequest extends FormRequest
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
    protected function prepareForValidation()
    {
        $this->merge([
            'old_images' => json_decode(request()->old_images, true),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'address' => 'nullable|max:250',
            'phone' => 'nullable|max:250',
            'api_key' => 'nullable|max:250',
            'status' => 'required|' . Rule::in(FarmStatus::ALL),
            'images' => 'nullable|array|max:5',
            'images.*' => 'file|max:5120|mimes:png,jpg,jpeg,webp,gif',
            'notes' => 'nullable|max:250',
        ];
    }

    public function attributes()
    {
        return [
            'images.*' => __('validation.attributes.image'),
        ];
    }
}
