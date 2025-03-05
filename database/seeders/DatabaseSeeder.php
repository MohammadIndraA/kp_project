<?php

namespace Database\Seeders;

use App\Models\ManagemenVideo;
use App\Models\Multimedia;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        // ManagemenVideo::factory(10)->create();
        // Multimedia::factory(10)->create();

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        $videos = [
            'judul' => fake()->sentence(),
            'deskripsi' => fake()->text(),
            'status' => rand(0, 1),
        ];

       
       for ($i=0; $i <10 ; $i++) { 
           ManagemenVideo::create($videos);
       }

       $video = ManagemenVideo::inRandomOrder()->first();
       $multimedia = [
        'path' => fake()->imageUrl(), // Generate URL gambar palsu
        'managemen_video_id' => $video->id, // Ambil ID dari video yang dipilih
        ];
        for ($i=0; $i < 10 ; $i++) { 
            Multimedia::create($multimedia);
        }

    }
}
