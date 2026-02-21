<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'role'          => $this->whenLoaded('role', fn () => [
                'id'   => $this->role->id,
                'name' => $this->role->name,
            ]),
            'nisn'          => $this->nisn,
            'phone'         => $this->phone,
            'address'       => $this->address,
            'school_origin' => $this->school_origin,
            'avatar'        => $this->avatar,
            'is_active'     => $this->is_active,
            'created_at'    => $this->created_at?->toISOString(),
        ];
    }
}
