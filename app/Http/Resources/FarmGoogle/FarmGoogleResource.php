<?php

namespace App\Http\Resources\FarmGoogle;

use App\Http\Resources\FarmResource;
use App\Services\V1\CommonService;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmGoogleResource extends JsonResource
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
            'label' => $this->label,
            'organisation_id' => $this->organisation_id,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'timezone' => $this->timezone,
            'scope' => $this->scope,

            'status' => $this->status,

            'deleted_at' => $this->deleted_at,
        ];
    }

    public function getImagesLInk($images)
    {
        return (new CommonService())->getImageLink($images);
    }
}
