<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

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
            'id'           => $this->id,
            'first_name'   => $this->first_name,
            'last_name'    => $this->last_name,
            'full_name'    => $this->full_name,
            'email'        => $this->email,
            'phone_number' => $this->phone_number,
            'color'        => $this->color,
            'created_at'   => (string) $this->created_at->format('d F Y H:i:s'),
            'updated_at'   => (string) $this->updated_at->format('d F Y H:i:s')
        ];
    }
}
