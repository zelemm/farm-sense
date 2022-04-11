<?php

namespace App\Http\Resources\Cattle;

use App\Http\Resources\FarmResource;
use App\Services\V1\CommonService;
use Illuminate\Http\Resources\Json\JsonResource;

class CattleResource extends JsonResource
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
            'farm_id' => $this->farm_id,
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
            'casterated' => $this->casterated,
            'vendor' => $this->vendor,
            'purchase_price' => $this->purchase_price,
            'sold_price' => $this->sold_price,
            'mother_cow' => $this->mother_cow,
            'father_cow' => $this->father_cow,
            'status' => $this->status,
            'notes' => $this->notes,
            'images' => is_array($this->images) ? $this->getImagesLInk($this->images) : [],

            'deleted_at' => $this->deleted_at,
        ];
    }

    public function getImagesLInk($images)
    {
        return (new CommonService())->getImageLink($images);
    }
}
