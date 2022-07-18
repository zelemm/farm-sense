<?php

namespace App\Http\Resources\FarmFence;

use Carbon\Carbon;
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
            'farm_fence_id' => $this->farm_fence_id,
            'fence_color' => $this->fence_color,
            'center_point' => $this->center_point,
            'fence_coordinates' => $this->fence_coordinates,

            'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('Y-m-d') : '',
            'deleted_at' => $this->deleted_at,
        ];
    }
}
