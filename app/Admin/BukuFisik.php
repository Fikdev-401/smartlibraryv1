<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class BukuFisik extends Model
{
    protected $table = 'buku_fisik';
    protected $primaryKey = 'id_buku_fisik';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_buku_fisik', 'id_kategori', 'judul', 'penulis', 'penerbit',
        'tahun', 'isbn', 'rak', 'stok', 'tersedia', 'deskripsi', 'id_sekolah',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id_buku_fisik)) {
                try {
                    $model->id_buku_fisik = Generator::uuid4()->toString();
                } catch (UnsatisfiedDependencyException $e) {
                    abort(500, $e->getMessage());
                }
            }
            if (is_null($model->tersedia)) {
                $model->tersedia = $model->stok ?? 0;
            }
        });
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku_fisik', 'id_buku_fisik');
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah', 'id_sekolah');
    }

    /**
     * Total number of physical book titles (optionally scoped to a school).
     */
    public static function getJmlBukuFisik($idSekolah = null)
    {
        $q = self::query();
        if ($idSekolah !== null) {
            $q->where('id_sekolah', $idSekolah);
        }
        return $q->count();
    }

    /**
     * Sum of "tersedia" copies (optionally scoped to a school).
     */
    public static function getJmlTersedia($idSekolah = null)
    {
        $q = self::query();
        if ($idSekolah !== null) {
            $q->where('id_sekolah', $idSekolah);
        }
        return (int) $q->sum('tersedia');
    }

    /**
     * Sum of (stok - tersedia) = copies currently on loan (optionally scoped).
     */
    public static function getJmlDipinjam($idSekolah = null)
    {
        $q = self::query();
        if ($idSekolah !== null) {
            $q->where('id_sekolah', $idSekolah);
        }
        return (int) $q->sum(DB::raw('stok - tersedia'));
    }
}