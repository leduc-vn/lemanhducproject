<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faces', function (Blueprint $table) {
            $table->id();
            $table->integer('faces_count');
            $table->longText('image');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faces');
    }
};
