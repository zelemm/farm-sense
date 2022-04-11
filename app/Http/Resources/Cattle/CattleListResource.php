<?php

namespace App\Http\Resources\Cattle;

use App\Http\Resources\FarmResource;
use App\Services\V1\CommonService;
use Illuminate\Http\Resources\Json\JsonResource;

class CattleListResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'farm_id' => $this->farm_id,
            'farm' => $this->farm ? new FarmResource($this->farm) : [],
            'mac_id' => $this->mac_id,
            'cattle_type' => $this->cattle_type,
            'name' => $this->name,
            'arrival' => $this->arrival,
            'date_of_birth' => $this->date_of_birth,
            'date_of_death' => $this->date_of_death,
            'date_of_sell' => $this->date_of_sell,
            'breed' => $this->breed,
            'sex' => $this->sex,
            'weight' => $this->weight,
            'mother' => $this->mother ? new CattleParentResource($this->mother) : [],
            'father' => $this->father ? new CattleParentResource($this->father) : [],
            'status' => $this->status,

            'deleted_at' => $this->deleted_at,
        ];
    }

    public function getImagesLInk($images)
    {
        return (new CommonService())->getImageLink($images);
    }
}
