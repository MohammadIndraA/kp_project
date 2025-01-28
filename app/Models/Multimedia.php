<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multimedia extends Model
{
    protected $table = 'multimedias';
    protected $guarded = [];

    public function managementvideos()
    {
        return $this->belongsTo(ManagemenVideo::class); 
    }

}