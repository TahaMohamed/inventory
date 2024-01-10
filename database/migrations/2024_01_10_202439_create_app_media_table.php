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
        Schema::create('app_media', function (Blueprint $table) {
            $table->id();
            $table->morphs('app_mediaable');
            $table->text('media')->nullable();
            $table->char('media_type', 15)->nullable(); // image - video - audio .....
            $table->string('option')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_media');
    }
};
