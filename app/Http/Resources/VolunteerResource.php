<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'password' => $this->auto_password,
            'email' => $this->email,
            'township' => $this->township,
            // Add more fields as needed
        ];
    }
}
