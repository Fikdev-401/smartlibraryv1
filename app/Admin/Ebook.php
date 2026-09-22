<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class Ebook extends Model
{
    protected $table = 'ebook';
    protected $primaryKey = 'id_ebook';
    protected $keyType = 'string';
    protected $fillable = ['id_ebook','id_kategori', 'id_user', 'judul', 'penulis', 'tahun', 'cover', 'file', 'created_at', 'updated_at',];

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

    /**
     * Count of ebooks. Accepts an optional id_user to scope to one uploader.
     * (Ebook is currently NOT school-scoped — its `id_user` points at the uploader
     * not a sekolah; left global for the dashboard.)
     */
    public static function getJmlEbook($idUser = null)
    {
        $q = DB::table('ebook');
        if ($idUser !== null) {
            $q->where('id_user', $idUser);
        }
        return $q->count();
    }
}