<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EbookResource extends JsonResource
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
            'id_ebook' => $this->id_ebook,
            'id_kategori' => $this->id_kategori,
            'id_user' => $this->id_user,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'tahun' => $this->tahun,
            'deskripsi' => $this->deskripsi,
            'cover' => $this->cover,
            'file' => $this->file,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function getUrl($data)
    {
        return "ok/".$data;
    } 
}
