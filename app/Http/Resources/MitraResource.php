<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MitraResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);return [
            'id_mitra' => $this->id_mitra,
            'id_user' => $this->id_user,
            'nama' => $this->nama,
            'logo' => $this->logo,
            'link_web' => $this->link_web,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
