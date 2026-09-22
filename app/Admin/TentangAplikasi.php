<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;
class TentangAplikasi extends Model
{
    //
    protected $table="tentang_aplikasi";
    protected $primaryKey="id_aplikasi";
    protected $keyType="string";
    protected $fillable = ['id_aplikasi', 'judul', 'deskripsi','gambar', 'created_at', 'updated_at',];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->id_aplikasi = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
