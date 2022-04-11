<?php

namespace App\Http\Resources;

use App\Services\V1\CommonService;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'notes' => $this->notes,
            'api_key' => $this->api_key,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'google_map' => $this->lat ?? '',
            'status' => $this->status,
            'images' => is_array($this->images) ? $this->getImagesLInk($this->images) : [],
            'deleted_at' => $this->deleted_at,
        ];
    }

    /**
     * @param $images
     * @return array|array[]
     */
    public function getImagesLInk($images)
    {
        return (new CommonService())->getImageLink($images);
    }

}
