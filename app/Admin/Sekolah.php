<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;
use DB;
class Sekolah extends Model
{
    //
    protected $table="sekolah";
    protected $primaryKey="id_sekolah";
    protected $keyType="string";
    protected $fillable = ['id_sekolah','nama_sekolah', 'created_at', 'updated_at',];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->id_sekolah = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }

}