<?php

namespace App\Http\Resources;

use App\Enums\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'role_id' => $this->role,
            'role' => $this->role,
            'user_type' => $this->user_type,
            'lang' => $this->lang,
            'origin_role' => $this->role,
            'farm' => $this->farm ? new FarmResource($this->farm) : []
        ];
    }
}
