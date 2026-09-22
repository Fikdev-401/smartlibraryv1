<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class Mitra extends Model
{
    protected $table = "mitra";
    protected $primaryKey = "id_mitra";
    protected $keyType ="string";
    protected $fillable = [
        'id_mitra', 'id_user', 'nama', 'logo', 'link_web', 
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->id_mitra = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
