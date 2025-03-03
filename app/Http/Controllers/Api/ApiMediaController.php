<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ManagemenVideo;

class ApiMediaController extends Controller
{
    public function index()
    {
        // Ambil semua data managemen_video beserta multimedia terkait
        $videos = ManagemenVideo::with('multimedias')->get();
        
        // Format data untuk API response
        $formattedVideos = $videos->map(function ($video) {
            return [
                'id' => $video->id,
                'judul' => $video->judul,
                'deskripsi' => $video->deskripsi,
                'status' => $video->status,
                'multimedias' => $video->multimedias->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'path' => $media->path,
                        'type' => $this->getMediaType($media->path),
                    ];
                }),
            ];
        });

        return response()->json($formattedVideos);
    }

    private function getMediaType($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        return in_array($extension, ['mp4', 'webm', 'ogg', 'mov', 'avi']) ? 'video' : 'image';
    }
}
