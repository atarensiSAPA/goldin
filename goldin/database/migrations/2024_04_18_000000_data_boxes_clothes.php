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
                'name' => 'Baby_shoes',
                'type' => 'Shoes',
                'price' => 80,
                'units' => 100,
                'clothes_img' => 'baby_shoes.png',
                'clothes_url' => 'https://th.bing.com/th/id/R.b37f855b242781d6604a11d89138465b?rik=1aLtpb6PYe2n8w&riu=http%3a%2f%2fwww.moonoloog.nl%2fwp-content%2fuploads%2f2016%2f06%2fbabykleding-520x400.jpg&ehk=kgD%2fYAqSQNYOTnbL2qnejMiWjjQ6wQb2kaeaa6ggiDo%3d&risl=&pid=ImgRaw&r=0',
            ],
            [
                'name' => 'Girl_shoes',
                'type' => 'Shoes',
                'price' => 95,
                'units' => 96,
                'clothes_img' => 'girl_shoes.png',
                'clothes_url' => 'https://stockitaly24.com/cdn/shop/collections/Calzature.jpg?v=1654615481&width=1500',
            ],
            [
                'name' => 'Black_shirt',
                'type' => 'Shirts',
                'price' => 1750,
                'units' => 10,
                'clothes_img' => 'black_shirt.webp',
                'clothes_url' => 'https://cdn.pixabay.com/photo/2016/12/06/09/30/blank-1886001_1280.png',
            ],
            [
                'name' => 'Green_shirt',
                'type' => 'Shirts',
                'price' => 105,
                'units' => 66,
                'clothes_img' => 'green_shirt.webp',
                'clothes_url' => 'https://cdn.pixabay.com/photo/2016/03/31/19/21/clothes-1294933_1280.png',
            ],
            [
                'name' => 'Red_hat',
                'type' => 'Hats',
                'price' => 170,
                'units' => 17,
                'clothes_img' => 'red_hat.webp',
                'clothes_url' => 'https://cdn.pixabay.com/photo/2016/04/01/11/32/hat-1300408_1280.png',
            ],
            [
                'name' => 'Blue_jeans',
                'type' => 'Jeans',
                'price' => 120,
                'units' => 25,
                'clothes_img' => 'blue_jeans.webp',
                'clothes_url' => 'https://cdn.pixabay.com/photo/2016/03/31/19/24/clothes-1294974_1280.png',
            ],
            [
                'name' => 'Green_socks',
                'type' => 'Socks',
                'price' => 246,
                'units' => 23,
                'clothes_img' => 'green_socks.webp',
                'clothes_url' => 'https://cdn.pixabay.com/photo/2017/01/31/23/04/clothes-2027993_1280.png',
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
