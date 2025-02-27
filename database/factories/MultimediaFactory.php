<?php

namespace Database\Factories;

use App\Models\ManagemenVideo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Multimedia>
 */
class MultimediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ambil satu record acak dari tabel managemen_videos
        $video = ManagemenVideo::inRandomOrder()->first();
    
        return [
            'path' => fake()->imageUrl(), // Generate URL gambar palsu
            'managemen_video_id' => $video->id, // Ambil ID dari video yang dipilih
        ];
    }
}
