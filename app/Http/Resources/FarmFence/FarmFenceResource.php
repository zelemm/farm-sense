<?php

namespace App\Http\Resources\FarmFence;

use App\Http\Resources\FarmResource;
use App\Services\V1\CommonService;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmFenceResource extends JsonResource
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
            'label' => $this->label,
            'description' => $this->description,

            'deleted_at' => $this->deleted_at,
        ];
    }
}
