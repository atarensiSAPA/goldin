<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('weapons', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('weapon_name');
        //     $table->string('weapon_skin');
        //     $table->text('description');
        //     $table->decimal('cost', 8, 2);
        //     $table->integer('units');
        //     $table->string('weapon_img');
        //     $table->timestamps();
        // });
        DB::table('weapons')->insert([
            'weapon_name' => 'AK-47',
            'weapon_skin' => 'Redline',
            'description' => 'The AK-47 is a powerful option that is favored by many players. It is a high-impact rifle that is capable of taking down enemies with a single shot to the head. The Redline skin is a popular choice for many players, as it features a sleek red and black design that is both stylish and intimidating.',
            'cost' => 2700.00,
            'units' => 100,
            'weapon_img' => 'ak47_redline.png',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapons');
    }
};
