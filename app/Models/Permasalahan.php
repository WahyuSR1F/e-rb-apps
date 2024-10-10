<?php

namespace App\Models;

use App\Models\User;
use App\Models\RencanaAksi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permasalahan extends Model
{
    use HasFactory;
    protected $table = "permasalahans";

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
        "erb_type_id",
        "user_id",
        "permasalahan",
        "sasaran",
        "indikator",
        "target",
        "unique_namespace",
    ];
    protected static function boot()
    {
        parent::boot();
        //cascade methode
        static::deleting(function ($a) {
            // menghapus pada semua child
            $a->renaksi()->each(function ($b) {
                $b->delete(); // Akan memicu penghapusan cascade pada Tabel C dan D
            });
        });
    }
    public function renaksi()
    {
        return $this->hasMany(RencanaAksi::class, 'permasalahan_id', 'id');
    }
    public function pembuat()
    {
        return  $this->belongsTo(User::class,'user_id', 'id');
    }
   
    public function allRelatedData()
    {
        return $this->renaksi()->with(['targetAnggaran', 'targetPenyelesaian', 'realisasiAnggaran', 'realisasiPenyelesaian','reject','FileAssets']);
    }
}