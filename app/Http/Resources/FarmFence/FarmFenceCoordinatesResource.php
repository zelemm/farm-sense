<?php

namespace App\Http\Resources\FarmFence;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmFenceCoordinatesResource extends JsonResource
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
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'updated_by_name' => $this->updated_by_name,

            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
