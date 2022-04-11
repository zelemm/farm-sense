<?php

namespace App\Http\Requests\FarmGoogle;

use App\Enums\CattleStatus;
use App\Enums\CattleArrivalStatus;
use App\Enums\CattleBreed;
use App\Enums\CattleSex;
use App\Enums\CattleType;
use App\Enums\FarmGoogleStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFarmGoogleRequest extends FormRequest
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
            'label' => 'required|max:250',
            'organisation_id' => 'required',
            'scope' => 'required',
            'client_id' => 'required',
            'client_secret' => 'required',
            'timezone' => 'required',
            'status' => 'required|' . Rule::in(FarmGoogleStatus::ALL),
        ];

        return $rules;

    }

}
