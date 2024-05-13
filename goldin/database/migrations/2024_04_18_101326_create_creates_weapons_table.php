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
            $table->decimal('price', 8, 2);
            $table->integer('units');
            $table->string('weapon_img');
            $table->string('rarity');
            $table->timestamps();
        });
        Schema::create('creates', function (Blueprint $table) {
            $table->id();
            $table->string('box_name');
            $table->integer('cost')->nullable();
            $table->unsignedBigInteger('weapon_id');
            $table->string("box_img")->nullable();
            $table->timestamps();
        
            $table->foreign('weapon_id')->references('id')->on('weapons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapons');
        Schema::dropIfExists('creates');
    }
};
