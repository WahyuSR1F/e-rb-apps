<?php

namespace App\Models;

use App\Models\Cluster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ERBType extends Model
{
    use HasFactory;
    protected $table = "erb_types";
    protected $casts = [
        'id' => 'string'
    ];

    public $incrementing = false;

    protected $keyTipe = 'string';
    protected $fillable = [
        'id',
        'nama',
        'type',
        'cluster_id'
    ];

    public function cluster () 
    {
        return  $this->belongsTo(Cluster::class);
    }
}