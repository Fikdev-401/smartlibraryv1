<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class Kategori extends Model
{
    protected $table = "kategori";
    protected $primaryKey = "id_kategori";
    protected $keyType ="string";
    protected $fillable = [
        'id_kategori', 'id_user', 'nama_kat', 
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->id_kategori = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
