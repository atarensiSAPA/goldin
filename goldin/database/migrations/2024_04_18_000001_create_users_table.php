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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('role')->default(0); // 0 = user, 1 = VIP, 2 = admin
            $table->integer('coins')->default(200);
            $table->integer('level')->default(1);
            $table->integer('experience')->default(0);
            $table->timestamp('vip_expires_at')->nullable();
        
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->string('external_id')->nullable();
            $table->string('external_auth')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_weapon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('weapon_id')->constrained();
            $table->timestamps();
        });

        Schema::create('user_creates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('create_id')->constrained();
            $table->timestamp('last_opened_at')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert([
            ['name' => 'admin', 'email' => 'admin@sapalomera.cat', 'password' => bcrypt('admin'), 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now(), 'role' => 2, 'coins' => 100, 'experience' => 120, 'avatar' => 'admin.jpg', 'external_id' => '123456', 'external_auth' => 'google'],
            ['name' => 'angel', 'email' => 'a.tarensi2@sapalomera.cat', 'password' => bcrypt('angel'), 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now(), 'role' => 0, 'coins' => 800, 'experience' => 220, 'avatar' => null, 'external_id' => null, 'external_auth' => null],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
