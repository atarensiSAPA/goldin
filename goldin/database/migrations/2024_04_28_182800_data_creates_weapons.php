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
        //weapons
        DB::table('weapons')->insert([
            'weapon_name' => 'AK-47',
            'weapon_skin' => 'Redline',
            'description' => 'The AK-47 is a powerful option that is favored by many players. It is a high-impact rifle that is capable of taking down enemies with a single shot to the head. The Redline skin is a popular choice for many players, as it features a sleek red and black design that is both stylish and intimidating.',
            'price' => 2700.00,
            'units' => 100,
            'weapon_img' => 'ak47_redline.png',
            'rarity' => 'epic',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('weapons')->insert([
            'weapon_name' => 'AWP',
            'weapon_skin' => 'Dragon-lore',
            'description' => 'The AWP is a powerful sniper rifle that is capable of taking down enemies with a single shot to the chest or head. The Dragon Lore skin is a rare and valuable option that is highly sought after by many players. It features a detailed dragon design that is both intricate and intimidating.',
            'price' => 4750.00,
            'units' => 25,
            'weapon_img' => 'awp-dragonlore.png',
            'rarity' => 'mitic',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('weapons')->insert([
            'weapon_name' => 'AK-47 2',
            'weapon_skin' => 'Redline 2',
            'description' => 'The AK-47 is a powerful option that is favored by many players. It is a high-impact rifle that is capable of taking down enemies with a single shot to the head. The Redline skin is a popular choice for many players, as it features a sleek red and black design that is both stylish and intimidating.',
            'price' => 2900.00,
            'units' => 50,
            'weapon_img' => 'ak47_redline.png',
            'rarity' => 'epic',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('weapons')->insert([
            'weapon_name' => 'AK-47 3',
            'weapon_skin' => 'Redline 3',
            'description' => 'The AK-47 is a powerful option that is favored by many players. It is a high-impact rifle that is capable of taking down enemies with a single shot to the head. The Redline skin is a popular choice for many players, as it features a sleek red and black design that is both stylish and intimidating.',
            'price' => 2900.00,
            'units' => 0,
            'weapon_img' => 'ak47_redline.png',
            'rarity' => 'legendary',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //creates

        DB::table('creates')->insert([
            'box_name' => 'AK-47-Redline-Box-1',
            'cost' => NULL,
            'weapon_id' => 2,
            'box_img' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('creates')->insert([
            'box_name' => 'AK-47-Redline-Box-1',
            'cost' => NULL,
            'weapon_id' => 2,
            'box_img' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('creates')->insert([
            'box_name' => 'AK-47-Redline-Box-1',
            'cost' => NULL,
            'weapon_id' => 4,
            'box_img' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('creates')->insert([
            'box_name' => 'AK-47-Redline-Box-1',
            'cost' => NULL,
            'weapon_id' => 3,
            'box_img' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('creates')->insert([
            'box_name' => 'AK-47-Redline-Box-1',
            'cost' => NULL,
            'weapon_id' => 3,
            'box_img' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('creates')->insert([
            'box_name' => 'AK-47-Redline-Box-1',
            'cost' => NULL,
            'weapon_id' => 3,
            'box_img' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('creates')->insert([
            'box_name' => 'AK-47-Redline-Box-1',
            'cost' => 200,
            'weapon_id' => 1,
            'box_img' => 'ak47_redline_box.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('creates')->insert([
            'box_name' => 'AK-47-Redline-Box-2',
            'cost' => 300,
            'weapon_id' => 1,
            'box_img' => 'ak47_redline_box.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('creates')->insert([
            'box_name' => 'AK-47-Redline-Box-3',
            'cost' => 400,
            'weapon_id' => 1,
            'box_img' => 'ak47_redline_box.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('creates')->insert([
            'box_name' => 'AK-47-Redline-Box-4',
            'cost' => 500,
            'weapon_id' => 1,
            'box_img' => 'ak47_redline_box.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('creates')->insert([
            'box_name' => 'AK-47-Redline-Box-5',
            'cost' => 600,
            'weapon_id' => 1,
            'box_img' => 'ak47_redline_box.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
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
