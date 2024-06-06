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
        $clothes = [
            [
                'name' => 'AK-47',
                'type' => 'Redline',
                'price' => 80,
                'units' => 100,
                'clothes_img' => 'ak47_redline.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s271.png?id=62393878d06b2520ef13',
            ],
            [
                'name' => 'AK-47',
                'type' => 'Asiimov',
                'price' => 95,
                'units' => 96,
                'clothes_img' => 'ak47_asiimov.png',
                'clothes_url' => 'https://cdn.sanity.io/images/dmtcrhxp/production/b620877c045afe6a54d356187688269b534c4026-1920x791.png?q=30&auto=format',
            ],
            [
                'name' => 'AWP',
                'type' => 'Dragon_lore',
                'price' => 1750,
                'units' => 10,
                'clothes_img' => 'awp_dragonlore.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s422.png?id=d0f53fa10778853316e9',
            ],
            [
                'name' => 'AWP',
                'type' => 'Wildfire',
                'price' => 105,
                'units' => 66,
                'clothes_img' => 'awp_wildfire.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s1113.png?id=558bf0e2c94980e20473',
            ],
            [
                'name' => 'Karambit',
                'type' => 'Case_hardened',
                'price' => 170,
                'units' => 17,
                'clothes_img' => 'karambit_case_hardened.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s331.png?id=a6eb1982f59b4715d828',
            ],
            [
                'name' => 'Karambit',
                'type' => 'Fade',
                'price' => 120,
                'units' => 25,
                'clothes_img' => 'karambit_fade.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s333.png?id=a7996f634199d4f900b7',
            ],
            [
                'name' => 'Bayonet',
                'type' => 'Lore',
                'price' => 246,
                'units' => 23,
                'clothes_img' => 'bayonet_lore.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s769.png?id=9ac78d94dbfbe65e5cd6',
            ],
            [
                'name' => 'USP',
                'type' => 'Kill_confirmed',
                'price' => 15,
                'units' => 246,
                'clothes_img' => 'usp_kill_confirmed.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s658.png?id=0126e2acae90c966d78b',
            ],
            [
                'name' => 'Falchion',
                'type' => 'Gamma_doppler',
                'price' => 320,
                'units' => 26,
                'clothes_img' => 'falchion_esmerald.png',
                'clothes_url' => 'https://steamcdn-a.akamaihd.net/apps/730/icons/econ/default_generated/weapon_knife_falchion_am_emerald_marbleized_light_large.86b54169ca00cfa482715e49b558e9e74776a669.png',
            ],
            [
                'name' => 'Famas',
                'type' => 'Rapid_eye_movement',
                'price' => 7,
                'units' => 312,
                'clothes_img' => 'famas_rapideyemovement.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s1513.png?id=6c4a57756120130550b0e891d2b46fc0',
            ],
            [
                'name' => 'M4A1-S',
                'type' => 'Hyper_beast',
                'price' => 79,
                'units' => 142,
                'clothes_img' => 'm4a1s_hyperbeast.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s554.png?id=9ad73873030cddcc7ca66196a326da2f',
            ],
            [
                'name' => 'M4A1-S',
                'type' => 'Welcome_to_the_jungle',
                'price' => 93,
                'units' => 175,
                'clothes_img' => 'm4a1s_welcomejungle.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s1359.png?id=f4f298f276ecec817e99ae4f9c76b8bd',
            ],
            [
                'name' => 'M9-Bayonet',
                'type' => 'Marble_fade',
                'price' => 374,
                'units' => 42,
                'clothes_img' => 'm9bayonet_marblefade.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s543.png?id=b34588cbd323b908046180ed6ebd0e9f',
            ],
            [
                'name' => 'Ssg-08',
                'type' => 'Blood_in_the_water',
                'price' => 212,
                'units' => 84,
                'clothes_img' => 'ssg_bloodwater.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s124.png?id=29b9c7c0cd3f1a4053a4608938985058',
            ],
            [
                'name' => 'Ssg-08',
                'type' => 'Dragon_fire',
                'price' => 64,
                'units' => 241,
                'clothes_img' => 'ssg_dragonfire.png',
                'clothes_url' => 'https://csgostash.com/storage/img/skin_sideview/s831.png?id=a0d3af1da934651872b051b46aba8ef0',
            ],

        ];
        
        foreach ($clothes as $c) {
            $c['created_at'] = now();
            $c['updated_at'] = now();
            $clothesIds[] = DB::table('clothes')->insertGetId($c);
        }

        //boxes

        $boxes = [
            [
                'box_name' => 'Box_level_1',
                'cost' => 0,
                'box_img' => 'level_1.png',
                'level' => 1,
            ],
            [
                'box_name' => 'Box_level_2',
                'cost' => 0,
                'box_img' => 'level_2.png',
                'level' => 2,
            ],
            [
                'box_name' => 'Box_level_3',
                'cost' => 0,
                'box_img' => 'level_3.png',
                'level' => 3,
            ],
            [
                'box_name' => 'Box_level_4',
                'cost' => 0,
                'box_img' => 'level_4.png',
                'level' => 4,
            ],
            [
                'box_name' => 'Box_level_5',
                'cost' => 0,
                'box_img' => 'level_5.png',
                'level' => 5,
            ],
            [
                'box_name' => 'Box_level_6',
                'cost' => 0,
                'box_img' => 'level_6.png',
                'level' => 6,
            ],
            [
                'box_name' => 'Box_level_7',
                'cost' => 0,
                'box_img' => 'level_7.png',
                'level' => 7,
            ],
        ];
        
        foreach ($boxes as $box) {
            $box['created_at'] = now();
            $box['updated_at'] = now();
            $boxesIds[] = DB::table('daily_boxes')->insertGetId($box);
        }

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
