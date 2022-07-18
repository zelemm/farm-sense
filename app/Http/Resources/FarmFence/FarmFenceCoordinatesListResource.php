<?php

namespace App\Http\Resources\FarmFence;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmFenceCoordinatesListResource extends JsonResource
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
            'center_point' => $this->center_point,
            'fence_color' => $this->fence_color,
            'fence_coordinates' => $this->fence_coordinates,
            'updated_by_name' => $this->updated_by_name,

            'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('Y-m-d') : '',
            'deleted_at' => $this->deleted_at,
        ];
    }
}
