<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForeignKeysOnUserWeaponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_weapons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->dropForeign(['weapon_id']);
            $table->foreign('weapon_id')->references('id')->on('weapons')->onDelete('cascade');
        });

        Schema::table('user_boxes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->dropForeign(['box_id']);
            $table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('box_weapons', function (Blueprint $table) {
            $table->dropForeign(['box_id']);
            $table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade')->onUpdate('cascade');

            $table->dropForeign(['weapon_id']);
            $table->foreign('weapon_id')->references('id')->on('boxes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_weapons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users');

            $table->dropForeign(['weapon_id']);
            $table->foreign('weapon_id')->references('id')->on('weapons');
        });

        Schema::table('user_boxes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users');

            $table->dropForeign(['box_id']);
            $table->foreign('box_id')->references('id')->on('boxes');
        });

        Schema::table('box_weapons', function (Blueprint $table) {
            $table->dropForeign(['box_id']);
            $table->foreign('box_id')->references('id')->on('boxes');

            $table->dropForeign(['weapon_id']);
            $table->foreign('weapon_id')->references('id')->on('boxes');
        });
    }
}
