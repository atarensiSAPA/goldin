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
        Schema::create('clothes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->integer('price');
            $table->integer('units');
            $table->string('clothes_img');
            $table->string('clothes_url');
            $table->timestamps();
        });
        Schema::create('daily_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('box_name')->unique();
            $table->integer('cost');
            $table->string("box_img")->nullable();
            $table->integer('level')->nullable();
            $table->boolean('available')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clothes');
        Schema::dropIfExists('daily_boxes');
    }
};
