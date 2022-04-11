<?php

namespace App\Http\Requests\Cattle;

use App\Enums\CattleStatus;
use App\Enums\CattleArrivalStatus;
use App\Enums\CattleBreed;
use App\Enums\CattleSex;
use App\Enums\CattleType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCattleRequest extends FormRequest
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
        $rules = [
            'farm_id' => 'required|exists:farms,id',
            'cattle_type' => 'required|' . Rule::in(CattleType::ALL),
            'mac_id' => 'required|max:250',

            'breed' => 'required|' . Rule::in(CattleBreed::ALL),

            'name' => 'required|max:100',
            'date_of_birth' => 'required|date|before:'. now()->addDay()->format('Y-m-d'),
            'sex' => 'required|' . Rule::in(CattleSex::ALL),
            'weight' => 'required',

            'arrival' => 'required|' . Rule::in(CattleArrivalStatus::ALL),
            'status' => 'required|' . Rule::in(CattleStatus::ALL),

            'mother_cow' => 'nullable|exists,cattles,id',
            'father_cow' => 'nullable|exists,cattles,id',

            'images' => 'nullable|array|max:5',
            'images.*' => 'file|max:5120|mimes:png,jpg,jpeg,webp,gif',
            'notes' => 'nullable|max:250',
        ];
        if(request()->sex){
            switch (request()->sex){
                case CattleSex::SIRE:
                    $rules['casterated'] = 'required';
                    break;
            }
        }
        if(request()->arrival){
            switch (request()->arrival){
                case CattleArrivalStatus::DIRECTFROMAUCTION: case CattleArrivalStatus::DIRECTFROMFARM:
                    $rules['vendor'] = 'required';
                    $rules['purchase_price'] = 'required';
                    break;
            }
        }
        if(request()->status){
            switch (request()->status){
                case CattleStatus::DEAD:
                    $rules['date_of_death'] = 'required';
                    break;
                case CattleStatus::SOLD:
                    $rules['sold_price'] = 'required';
                    $rules['date_of_sell'] = 'required';
                    break;
            }
        }
        return $rules;

    }

    public function attributes()
    {
        return [
            'images.*' => __('validation.attributes.image'),
        ];
    }
}
