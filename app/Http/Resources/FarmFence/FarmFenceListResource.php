<?php

namespace App\Http\Resources\FarmFence;

use App\Http\Resources\FarmResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmFenceListResource extends JsonResource
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
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,

            'deleted_at' => $this->deleted_at,
        ];
    }

}
