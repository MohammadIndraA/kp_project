<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagemenVideo extends Model
{
    protected $table = 'managemen_videos';

    protected $fillable = [
        'judul',
        'deskripsi',
    ];

    public function multimedias(){
        return $this->hasMany(Multimedia::class);
    }

    public function ManagemenVideo_multimedias(){
        return $this->belongsToMany(Multimedia::class, 'managemen_video_multimedia', 'managemen_video_id', 'multimedia_id');
    }
}
