<?php

namespace App\Http\Resources\CattleLocation;

use Illuminate\Http\Resources\Json\JsonResource;

class CattleLocationResource extends JsonResource
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
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'green_zone' => $this->green_zone,
            'purple_zone' => $this->purple_zone,
            'orange_zone' => $this->orange_zone,
            'red_zone' => $this->red_zone,
            'grazing' => $this->grazing,
            'standing' => $this->standing,
            'sitting' => $this->sitting,
            'location_date' => $this->location_date,
        ];
    }
}
