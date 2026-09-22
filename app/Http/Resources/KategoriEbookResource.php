<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KategoriEbookResource extends JsonResource
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
            'id_kategori' => $this->id_kategori,
            'id_user' => $this->id_user,
            'nama_kat' => $this->nama_kat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
