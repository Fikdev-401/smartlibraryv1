<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_peminjaman', 'id_buku_fisik', 'id_user_peminjam', 'id_operator',
        'nama_peminjam', 'kontak_peminjam',
        'tanggal_pinjam', 'tanggal_rencana_kembali', 'status',
    ];

    protected $casts = [
        'tanggal_pinjam'          => 'date',
        'tanggal_rencana_kembali' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id_peminjaman)) {
                try {
                    $model->id_peminjaman = Generator::uuid4()->toString();
                } catch (UnsatisfiedDependencyException $e) {
                    abort(500, $e->getMessage());
                }
            }
            if (empty($model->status)) {
                $model->status = 'DIPINJAM';
            }
        });
    }

    public function bukuFisik()
    {
        return $this->belongsTo(BukuFisik::class, 'id_buku_fisik', 'id_buku_fisik');
    }

    public function peminjam()
    {
        return $this->belongsTo(\App\User::class, 'id_user_peminjam', 'id');
    }

    public function operator()
    {
        return $this->belongsTo(\App\User::class, 'id_operator', 'id');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_peminjaman', 'id_peminjaman');
    }

    /**
     * Calculate how many days late (compared to today).
     * Positive when overdue, 0 when on time.
     */
    public function getHariTerlambatAttribute()
    {
        $now = Carbon::today();
        if ($this->status === 'DIKEMBALIKAN' && $this->pengembalian) {
            $now = Carbon::parse($this->pengembalian->tanggal_kembali);
        }
        return max(0, $now->diffInDays(Carbon::parse($this->tanggal_rencana_kembali), false) * -1);
    }

    public function isOverdue()
    {
        if ($this->status === 'DIKEMBALIKAN') {
            return false;
        }
        return Carbon::parse($this->tanggal_rencana_kembali)->lt(Carbon::today());
    }

    /**
     * Apply school scope by joining through buku_fisik.id_sekolah.
     * Returns a clone of the query builder.
     */
    protected static function applySchoolScope($q, $idSekolah)
    {
        if ($idSekolah === null) {
            return $q;
        }
        return $q->whereIn('peminjaman.id_buku_fisik', function ($sub) use ($idSekolah) {
            $sub->select('id_buku_fisik')->from('buku_fisik')->where('id_sekolah', $idSekolah);
        });
    }

    /**
     * Number of loans that are currently active (DIPINJAM or TERLAMBAT).
     * Pass an id_sekolah to scope to a school.
     */
    public static function getJmlAktif($idSekolah = null)
    {
        $q = self::whereIn('status', ['DIPINJAM','TERLAMBAT']);
        $q = self::applySchoolScope($q, $idSekolah);
        return $q->count();
    }

    /**
     * Number of loans that are currently overdue (status != returned AND planned return < today).
     * Pass an id_sekolah to scope to a school.
     */
    public static function getJmlTerlambat($idSekolah = null)
    {
        $q = self::where('status','!=','DIKEMBALIKAN')
            ->whereDate('tanggal_rencana_kembali','<',Carbon::today()->toDateString());
        $q = self::applySchoolScope($q, $idSekolah);
        return $q->count();
    }
}