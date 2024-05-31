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
            [
                'weapon_name' => 'Falchion',
                'weapon_skin' => 'Gamma_doppler',
                'description' => 'The Falchion is a unique and deadly knife that is favored by many players. It features a curved blade that is capable of inflicting serious damage on enemies. The Gamma Doppler skin is a rare and valuable option that is highly sought after by many players. It features a detailed green and black design that is both stylish and intimidating.',
                'price' => 320,
                'units' => 26,
                'weapon_img' => 'falchion_esmerald.png',
                'rarity' => 'legendary',
                'weapon_url' => 'https://steamcdn-a.akamaihd.net/apps/730/icons/econ/default_generated/weapon_knife_falchion_am_emerald_marbleized_light_large.86b54169ca00cfa482715e49b558e9e74776a669.png',
            ],
            [
                'weapon_name' => 'Famas',
                'weapon_skin' => 'Rapid_eye_movement',
                'description' => 'The Famas is a reliable and accurate rifle that is favored by many players. It is a high-impact weapon that is capable of taking down enemies with a single shot to the head. The Rapid Eye Movement skin is a rare and valuable option that is highly sought after by many players. It features a detailed blue and black design that is both stylish and intimidating.',
                'price' => 7,
                'units' => 312,
                'weapon_img' => 'famas_rapideyemovement.png',
                'rarity' => 'common',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s1513.png?id=6c4a57756120130550b0e891d2b46fc0',
            ],
            [
                'weapon_name' => 'M4A1-S',
                'weapon_skin' => 'Hyper_beast',
                'description' => 'The M4A1-S is a reliable and accurate rifle that is favored by many players. It is a high-impact weapon that is capable of taking down enemies with a single shot to the head. The Hyper Beast skin is a rare and valuable option that is highly sought after by many players. It features a detailed blue and black design that is both stylish and intimidating.',
                'price' => 79,
                'units' => 142,
                'weapon_img' => 'm4a1s_hyperbeast.png',
                'rarity' => 'epic',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s554.png?id=9ad73873030cddcc7ca66196a326da2f',
            ],
            [
                'weapon_name' => 'M4A1-S',
                'weapon_skin' => 'Welcome_to_the_jungle',
                'description' => 'The M4A1-S is a reliable and accurate rifle that is favored by many players. It is a high-impact weapon that is capable of taking down enemies with a single shot to the head. The Hyper Beast skin is a rare and valuable option that is highly sought after by many players. It features a detailed blue and black design that is both stylish and intimidating.',
                'price' => 93,
                'units' => 175,
                'weapon_img' => 'm4a1s_welcomejungle.png',
                'rarity' => 'epic',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s1359.png?id=f4f298f276ecec817e99ae4f9c76b8bd',
            ],
            [
                'weapon_name' => 'M9-Bayonet',
                'weapon_skin' => 'Marble_fade',
                'description' => 'The M9-Bayonet is a versatile and deadly knife that is favored by many players. It features a sharp blade that is capable of inflicting serious damage on enemies. The Marble Fade skin is a rare and valuable option that is highly sought after by many players. It features a detailed rainbow design that is both stylish and intimidating.',
                'price' => 374,
                'units' => 42,
                'weapon_img' => 'm9bayonet_marblefade.png',
                'rarity' => 'legendary',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s543.png?id=b34588cbd323b908046180ed6ebd0e9f',
            ],
            [
                'weapon_name' => 'Ssg-08',
                'weapon_skin' => 'Blood_in_the_water',
                'description' => 'The SSG-08 is a reliable and accurate sniper rifle that is favored by many players. It is a high-impact weapon that is capable of taking down enemies with a single shot to the head. The Blood in the Water skin is a rare and valuable option that is highly sought after by many players. It features a detailed red and black design that is both stylish and intimidating.',
                'price' => 212,
                'units' => 84,
                'weapon_img' => 'ssg_bloodwater.png',
                'rarity' => 'epic',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s124.png?id=29b9c7c0cd3f1a4053a4608938985058',
            ],
            [
                'weapon_name' => 'Ssg-08',
                'weapon_skin' => 'Dragon_fire',
                'description' => 'The SSG-08 is a reliable and accurate sniper rifle that is favored by many players. It is a high-impact weapon that is capable of taking down enemies with a single shot to the head. The Blood in the Water skin is a rare and valuable option that is highly sought after by many players. It features a detailed red and black design that is both stylish and intimidating.',
                'price' => 64,
                'units' => 241,
                'weapon_img' => 'ssg_dragonfire.png',
                'rarity' => 'rare',
                'weapon_url' => 'https://csgostash.com/storage/img/skin_sideview/s831.png?id=a0d3af1da934651872b051b46aba8ef0',
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
                'box_name' => 'Batman_who_laughs',
                'cost' => 1000,
                'box_img' => 'batman_who_laughs.png',
            ],
            [
                'box_name' => 'Green_lantern',
                'cost' => 400,
                'box_img' => 'green_lantern.png',
            ],
            [
                'box_name' => 'Harley_queen',
                'cost' => 600,
                'box_img' => 'harley_quinn.png',
            ],
            [
                'box_name' => 'Superman',
                'cost' => 700,
                'box_img' => 'superman.png',
            ],
            [
                'box_name' => 'Wonderwoman',
                'cost' => 300,
                'box_img' => 'wonderwoman.png',
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
            [
                'box_name' => 'Box_level_3',
                'cost' => 0,
                'box_img' => 'level_3.png',
                'daily' => true,
                'level' => 3,
            ],
            [
                'box_name' => 'Box_level_4',
                'cost' => 0,
                'box_img' => 'level_4.png',
                'daily' => true,
                'level' => 4,
            ],
            [
                'box_name' => 'Box_level_5',
                'cost' => 0,
                'box_img' => 'level_5.png',
                'daily' => true,
                'level' => 5,
            ],
            [
                'box_name' => 'Box_level_6',
                'cost' => 0,
                'box_img' => 'level_6.png',
                'daily' => true,
                'level' => 6,
            ],
            [
                'box_name' => 'Box_level_7',
                'cost' => 0,
                'box_img' => 'level_7.png',
                'daily' => true,
                'level' => 7,
            ],
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
            [
                'box_id' => $boxesIds[2], // Batman_who_laughs box
                'weapon_ids' => [$weaponIds[7], $weaponIds[8], $weaponIds[9], $weaponIds[10], $weaponIds[11], $weaponIds[12]],
            ],
            [
                'box_id' => $boxesIds[3], // Green_lantern box
                'weapon_ids' => [$weaponIds[2], $weaponIds[14], $weaponIds[12], $weaponIds[13], $weaponIds[7], $weaponIds[4]],
            ],
            [
                'box_id' => $boxesIds[4], // Harley_queen box
                'weapon_ids' => [$weaponIds[1], $weaponIds[3], $weaponIds[5], $weaponIds[7], $weaponIds[9], $weaponIds[11]],
            ],
            [
                'box_id' => $boxesIds[5], // Superman box
                'weapon_ids' => [$weaponIds[5], $weaponIds[2], $weaponIds[3], $weaponIds[6], $weaponIds[11], $weaponIds[10]],
            ],
            [
                'box_id' => $boxesIds[6], // Wonderwoman box
                'weapon_ids' => [$weaponIds[7], $weaponIds[8], $weaponIds[10], $weaponIds[11], $weaponIds[14], $weaponIds[4], $weaponIds[2], $weaponIds[1]],
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
