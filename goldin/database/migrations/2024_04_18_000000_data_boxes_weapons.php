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
        $weapons = [
            [
                'weapon_name' => 'AK-47',
                'weapon_skin' => 'Redline',
                'description' => 'The AK-47 is a powerful option that is favored by many players. It is a high-impact rifle that is capable of taking down enemies with a single shot to the head. The Redline skin is a popular choice for many players, as it features a sleek red and black design that is both stylish and intimidating.',
                'price' => 80,
                'units' => 100,
                'weapon_img' => 'ak47_redline.png',
                'rarity' => 'epic',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s271.png?id=62393878d06b2520ef13',
            ],
            [
                'weapon_name' => 'AK-47',
                'weapon_skin' => 'Asiimov',
                'description' => 'The AK-47 is a powerful option that is favored by many players. It is a high-impact rifle that is capable of taking down enemies with a single shot to the head. The Redline skin is a popular choice for many players, as it features a sleek red and black design that is both stylish and intimidating.',
                'price' => 95,
                'units' => 96,
                'weapon_img' => 'ak47_asiimov.png',
                'rarity' => 'epic',
                'weapon_url' => 'https://cdn.sanity.io/images/dmtcrhxp/production/b620877c045afe6a54d356187688269b534c4026-1920x791.png?q=30&auto=format',
            ],
            [
                'weapon_name' => 'AWP',
                'weapon_skin' => 'Dragon_lore',
                'description' => 'The AWP is a powerful sniper rifle that is capable of taking down enemies with a single shot to the chest or head. The Dragon Lore skin is a rare and valuable option that is highly sought after by many players. It features a detailed dragon design that is both intricate and intimidating.',
                'price' => 1750,
                'units' => 10,
                'weapon_img' => 'awp_dragonlore.png',
                'rarity' => 'mitic',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s422.png?id=d0f53fa10778853316e9',
            ],
            [
                'weapon_name' => 'AWP',
                'weapon_skin' => 'Wildfire',
                'description' => 'The AWP is a powerful sniper rifle that is capable of taking down enemies with a single shot to the chest or head. The Dragon Lore skin is a rare and valuable option that is highly sought after by many players. It features a detailed dragon design that is both intricate and intimidating.',
                'price' => 105,
                'units' => 66,
                'weapon_img' => 'awp_wildfire.png',
                'rarity' => 'epic',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s1113.png?id=558bf0e2c94980e20473',
            ],
            [
                'weapon_name' => 'Karambit',
                'weapon_skin' => 'Case_hardened',
                'description' => 'The Karambit is a unique and deadly knife that is favored by many players. It features a curved blade that is capable of inflicting serious damage on enemies. The Case Hardened skin is a rare and valuable option that is highly sought after by many players. It features a detailed blue and gold design that is both stylish and intimidating.',
                'price' => 170,
                'units' => 17,
                'weapon_img' => 'karambit_case_hardened.png',
                'rarity' => 'legendary',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s331.png?id=a6eb1982f59b4715d828',
            ],
            [
                'weapon_name' => 'Karambit',
                'weapon_skin' => 'Fade',
                'description' => 'The Karambit is a unique and deadly knife that is favored by many players. It features a curved blade that is capable of inflicting serious damage on enemies. The Case Hardened skin is a rare and valuable option that is highly sought after by many players. It features a detailed blue and gold design that is both stylish and intimidating.',
                'price' => 120,
                'units' => 25,
                'weapon_img' => 'karambit_fade.png',
                'rarity' => 'legendary',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s333.png?id=a7996f634199d4f900b7',
            ],
            [
                'weapon_name' => 'Bayonet',
                'weapon_skin' => 'Lore',
                'description' => 'The Bayonet is a versatile and deadly knife that is favored by many players. It features a sharp blade that is capable of inflicting serious damage on enemies. The Fade skin is a rare and valuable option that is highly sought after by many players. It features a detailed rainbow design that is both stylish and intimidating.',
                'price' => 246,
                'units' => 23,
                'weapon_img' => 'bayonet_lore.png',
                'rarity' => 'legendary',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s769.png?id=9ac78d94dbfbe65e5cd6',
            ],
            [
                'weapon_name' => 'USP',
                'weapon_skin' => 'Kill_confirmed',
                'description' => 'The USP is a reliable and accurate pistol that is favored by many players. It is a high-impact weapon that is capable of taking down enemies with a single shot to the head. The Kill Confirmed skin is a rare and valuable option that is highly sought after by many players. It features a detailed gold and black design that is both stylish and intimidating.',
                'price' => 15,
                'units' => 246,
                'weapon_img' => 'usp_kill_confirmed.png',
                'rarity' => 'rare',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s658.png?id=0126e2acae90c966d78b',
            ],

        ];
        
        foreach ($weapons as $weapon) {
            $weapon['created_at'] = now();
            $weapon['updated_at'] = now();
            $weaponIds[] = DB::table('weapons')->insertGetId($weapon);
        }

        //boxes

        $boxes = [
            [
                'box_name' => 'The_villain',
                'cost' => 500,
                'box_img' => 'the_villain.png',
            ],
            [
                'box_name' => 'The_heroe',
                'cost' => 800,
                'box_img' => 'the_heroe.png',
            ],
            [
                'box_name' => 'Box_level_1',
                'cost' => 0,
                'box_img' => 'level_1.png',
                'daily' => true,
                'level' => 1,
            ],
            [
                'box_name' => 'Box_level_2',
                'cost' => 0,
                'box_img' => 'level_2.png',
                'daily' => true,
                'level' => 2,
            ],
            // ... other boxes ...
        ];
        
        foreach ($boxes as $box) {
            $box['created_at'] = now();
            $box['updated_at'] = now();
            $boxesIds[] = DB::table('boxes')->insertGetId($box);
        }

        // Insert relationships into box_weapons
        $box_weapons = [
            [
                'box_id' => $boxesIds[0], // The_villain box
                'weapon_ids' => [$weaponIds[0], $weaponIds[1], $weaponIds[2], $weaponIds[3], $weaponIds[4], $weaponIds[5], $weaponIds[6], $weaponIds[7]],
            ],
            [
                'box_id' => $boxesIds[1], // The_heroe box
                'weapon_ids' => [$weaponIds[2], $weaponIds[3], $weaponIds[4], $weaponIds[5], $weaponIds[6], $weaponIds[7]],
            ],
        ];

        foreach ($box_weapons as $cw) {
            foreach ($cw['weapon_ids'] as $weaponId) {
                DB::table('box_weapons')->insert([
                    'box_id' => $cw['box_id'],
                    'weapon_id' => $weaponId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

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
