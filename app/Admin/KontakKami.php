<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;
class KontakKami extends Model
{
    //
    protected $table="kontak_kami";
    protected $primaryKey="id_kontak";
    protected $keyType="string";
    protected $fillable = ['id_kontak','nama','email','pesan', 'created_at', 'updated_at',];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->id_kontak = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
