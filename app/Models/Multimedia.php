<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multimedia extends Model
{
    use HasFactory; // Tambahkan ini
    protected $table = 'multimedias';
    protected $guarded = [];

    public function managementvideos()
    {
        return $this->belongsTo(ManagemenVideo::class); 
    }

}
