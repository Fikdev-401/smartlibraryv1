<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class Ebook extends Model
{
    protected $table = "ebook";
    protected $primaryKey = "id_ebook";
    protected $keyType ="string";
    protected $fillable = [
        'id_ebook', 'id_kategori', 'id_user', 'judul', 'penulis', 'tahun', 'deskripsi', 'cover', 'file', 
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->id_ebook = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
