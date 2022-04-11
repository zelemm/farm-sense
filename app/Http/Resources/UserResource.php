<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
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
            'email' => $this->email,
            'user_type' => $this->user_type,
            'lang' => $this->lang,
            'roles' => $this->roles,
            'farm' => $this->farm ? new FarmResource($this->farm) : [],

            'deleted_at' => $this->deleted_at,
        ];
    }
}
