<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class Video extends Model
{
    protected $table = 'videos';
    protected $primaryKey = 'id_video';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_video', 'judul', 'kategori_video', 'youtube_url', 'youtube_id',
        'deskripsi', 'id_user', 'id_sekolah', 'aktif',
    ];

    /**
     * Kategori video yang diizinkan (predefined, konsisten dengan dropdown form).
     * Kalau mau nambah kategori: ubah di sini + di controller + di view.
     */
    public const KATEGORI = [
        'Edukasi'   => 'Edukasi',
        'Hiburan'   => 'Hiburan',
        'Tutorial'  => 'Tutorial',
        'Berita'    => 'Berita',
        'Lainnya'   => 'Lainnya',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id_video)) {
                try {
                    $model->id_video = Generator::uuid4()->toString();
                } catch (UnsatisfiedDependencyException $e) {
                    abort(500, $e->getMessage());
                }
            }
        });
    }

    /**
     * Extract video ID (11 char) dari URL YouTube dalam format apapun.
     * Return null kalau tidak terdeteksi.
     *
     * Mendukung:
     *   - https://www.youtube.com/watch?v=ID
     *   - https://youtu.be/ID
     *   - https://www.youtube.com/embed/ID
     *   - https://www.youtube.com/shorts/ID
     *   - https://m.youtube.com/watch?v=ID
     *   - URL dengan query string tambahan (si=, t=, dll)
     */
    public static function extractYouTubeId($url)
    {
        if (!is_string($url) || trim($url) === '') {
            return null;
        }
        $url = trim($url);

        // Pattern order matters: cek yang paling spesifik dulu
        $patterns = [
            // youtu.be/ID (tidak ada query string setelah ID)
            '#youtu\.be/([a-zA-Z0-9_-]{11})#',
            // youtube.com/embed/ID
            '#youtube\.com/embed/([a-zA-Z0-9_-]{11})#',
            // youtube.com/shorts/ID
            '#youtube\.com/shorts/([a-zA-Z0-9_-]{11})#',
            // youtube.com/watch?v=ID  (ID bisa muncul sebelum/d sesudah param lain)
            '#youtube\.com/watch\?(?:[^&\s]*&)*v=([a-zA-Z0-9_-]{11})#',
        ];

        foreach ($patterns as $p) {
            if (preg_match($p, $url, $m)) {
                return $m[1];
            }
        }
        return null;
    }

    /**
     * URL embed YouTube yang siap dipakai di <iframe src=...>
     */
    public function getEmbedUrlAttribute()
    {
        return 'https://www.youtube.com/embed/'.$this->youtube_id;
    }

    /**
     * URL thumbnail YouTube (maxresdefault tidak selalu ada, fallback ke hqdefault)
     */
    public function getThumbnailUrlAttribute()
    {
        return 'https://i.ytimg.com/vi/'.$this->youtube_id.'/hqdefault.jpg';
    }
}
