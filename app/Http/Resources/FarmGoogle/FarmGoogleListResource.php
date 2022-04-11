<?php

namespace App\Http\Resources\FarmGoogle;

use App\Http\Resources\FarmResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmGoogleListResource extends JsonResource
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
            'organisation_id' => $this->organisation_id,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'timezone' => $this->timezone,
            'status' => $this->status,
            'access_token' => $this->access_token,

            'deleted_at' => $this->deleted_at,
        ];
    }

}
