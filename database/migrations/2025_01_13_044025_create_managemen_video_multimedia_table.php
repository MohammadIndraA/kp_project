<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('managemen_video_multimedia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('managemen_video_id')->unsigned();
            $table->unsignedBigInteger('multimedia_id')->unsigned();
            $table->foreign('managemen_video_id')->references('id')->on('managemen_videos')->onDelete('cascade');
            $table->foreign('multimedia_id')->references('id')->on('multimedias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('managemen_video_multimedia');
    }
};
