<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';
    protected $primaryKey = 'id_pengembalian';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_pengembalian', 'id_peminjaman', 'id_operator',
        'tanggal_kembali', 'hari_terlambat', 'denda', 'catatan',
    ];

    protected $casts = [
        'tanggal_kembali' => 'date',
        'hari_terlambat'  => 'integer',
        'denda'           => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id_pengembalian)) {
                try {
                    $model->id_pengembalian = Generator::uuid4()->toString();
                } catch (UnsatisfiedDependencyException $e) {
                    abort(500, $e->getMessage());
                }
            }
        });
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function operator()
    {
        return $this->belongsTo(\App\User::class, 'id_operator', 'id');
    }
}