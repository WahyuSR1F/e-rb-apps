<?php

namespace App\Models;

use App\Models\Reject;
use App\Models\Permasalahan;
use App\Models\TargetAnggaran;
use App\Models\RealisasiAnggaran;
use App\Models\TargetPenyelesaian;
use App\Models\RealisasiPenyelesaian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RencanaAksi extends Model
{
    use HasFactory;

    protected $table = "rencana_aksis";

    protected $primaryKey = 'id';

    // Pastikan UUID di-cast sebagai string
    protected $casts = [
        'id' => 'string',
    ];

    public $incrementing = false;

    // Set jenis key type
    protected $keyType = 'string';

    protected $fillable = [
        "id",
        "permasalahan_id",
        "rencana_aksi",
        "user_id",
        "indikator",
        "satuan",
        "koordinator",
        "pelaksana",
        'unique_namespace'
    ];
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($b) {
            $b->targetPenyelesaian()->delete();
            $b->targetAnggaran()->delete();
            $b->realisasiAnggaran()->delete();
            $b->realisasiPenyelesaian()->delete();
            $b->FileAssets()->delete();
        });
    }
    public function permasalahan()
    {
        return $this->belongsTo(Permasalahan::class, 'rencana_aksi_id', 'id');
    }

    public function targetAnggaran()
    {
        return $this->hasOne(TargetAnggaran::class);
    }
    public function realisasiAnggaran()
    {
        return $this->hasOne(RealisasiAnggaran::class);
    }
    public function  targetPenyelesaian()
    {
        return $this->hasOne(TargetPenyelesaian::class);
    }
    public function realisasiPenyelesaian()
    {
        return $this->hasOne(RealisasiPenyelesaian::class);
    }
    public function reject()
    {
        return $this->hasOne(Reject::class);
    }

    public function FileAssets()
    {
        return $this->hasOne(FileAsset::class);
    }
}