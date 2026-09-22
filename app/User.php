<?php

namespace App;

use Laravel\Passport\HasApiTokens;      
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;
use DB;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table="users";
    protected $primaryKey="id";
    protected $keyType="string";
    protected $fillable = [
        'name', 'email', 'password', 'level','id_sekolah', 'aktif', 'kontak', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->id = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }

    /**
     * Count of MEMBER users. Pass an id_sekolah to scope to a specific school.
     */
    public static function getJumlahMember($idSekolah = null)
    {
        $q = DB::table('users')->where('level','=','MEMBER');
        if ($idSekolah !== null) {
            $q->where('id_sekolah', $idSekolah);
        }
        $count = $q->count();
        return $count;
    }
    
    public static function getJmlMemberPerSekolah($idsekolah)
    {
        $qty=DB::table('users')->where('id_sekolah','=',$idsekolah)->count();
        return $qty;
    }
    
    public static function getMemberBaru()
    {
        $data=DB::table('users')->select('users.*','sekolah.nama_sekolah')
            ->leftJoin('sekolah','users.id_sekolah','=','sekolah.id_sekolah')
            ->where('users.level','=','MEMBER')
            ->where('users.aktif','=','N')
            ->orderBy('users.created_at','DESC')
            ->get();
        return $data;
    }
}
