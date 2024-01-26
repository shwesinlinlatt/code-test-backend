<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sex' => $this->sex,
            'age' => $this->age,
            'address' => $this->address,
            'treatment_start_date' => $this->treatment_start_date,
            'is_VOT' => $this->is_VOT,
            'volunteer_id' => $this->volunteer_id,
            'volunteer' => new VolunteerResource($this->volunteer)
            // Add more fields as needed
        ];
    }
}
