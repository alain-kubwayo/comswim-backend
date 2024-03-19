<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'self' => $this->self,
            'created_at' => $this->created_at,
            'user_profile' => new UserProfileResource($this->userProfile),
            'address' => new AddressResource($this->address),
            'health_info' => new HealthInfoResource($this->healthInfo),
            'guardian' => new GuardianResource($this->guardian),
        ];
    }
}
