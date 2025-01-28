<?php

namespace App\Http\Controllers;

use App\Models\ManagemenVideo;
use illuminate\Http\Request;

class TvMediaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua data managemen_video beserta multimedia terkait
        $videos = ManagemenVideo::with('multimedias')->get();

        return  view('media-view.index', compact('videos'));
    }
}