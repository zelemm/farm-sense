<?php

namespace App\Http\Requests\FarmFence;

use App\Enums\CattleStatus;
use App\Enums\CattleArrivalStatus;
use App\Enums\CattleBreed;
use App\Enums\CattleSex;
use App\Enums\CattleType;
use App\Enums\FarmGoogleStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFarmFenceCoordsRequest extends FormRequest
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
        $rules = [
            'farm_fence_id' => 'required|exists:farms,id',
            'places' => 'required|array',
            'center' => 'required|array'
        ];
//        'latitude' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
//            'longitude' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        return $rules;

    }
}
