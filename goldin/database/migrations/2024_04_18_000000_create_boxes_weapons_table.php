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
        Schema::create('weapons', function (Blueprint $table) {
            $table->id();
            $table->string('weapon_name');
            $table->string('weapon_skin');
            $table->text('description');
            $table->integer('price');
            $table->integer('units');
            $table->string('weapon_img');
            $table->string('rarity');
            $table->timestamps();
        });
        Schema::create('boxes', function (Blueprint $table) {
            $table->id();
            $table->string('box_name')->unique();
            $table->integer('cost');
            $table->string("box_img")->nullable();
            $table->boolean('daily')->default(0);
            $table->integer('level')->nullable();
            $table->timestamps();
        });
        
        Schema::create('box_weapons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('box_id')->constrained('boxes');
            $table->foreignId('weapon_id')->constrained('weapons');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapons');
        Schema::dropIfExists('boxes');
    }
};
