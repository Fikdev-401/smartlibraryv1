<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;
class ImageHeader extends Model
{
    //
    protected $table="image_header";
    protected $primaryKey="id_image";
    protected $keyType="string";
    protected $fillable = ['id_image','image', 'created_at', 'updated_at',];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->id_image = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
