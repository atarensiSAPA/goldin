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
            $table->boolean('connected')->default(0);
            $table->boolean('is_kicked')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->string('external_id')->nullable();
            $table->string('external_auth')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('box_id')->constrained('daily_boxes');
            $table->timestamp('last_opened_at')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('clothes_id')->constrained();
            $table->timestamp('purchase_date');
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });

        DB::table('users')->insert([
            ['name' => 'admin', 'email' => 'admin@sapalomera.cat', 'password' => bcrypt('admin'), 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now(), 'role' => 2, 'coins' => 100, 'experience' => 120, 'avatar' => 'admin.jpg', 'external_id' => '123456', 'external_auth' => 'google'],
            ['name' => 'angel', 'email' => 'a.tarensi2@sapalomera.cat', 'password' => bcrypt('angel'), 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now(), 'role' => 0, 'coins' => 800, 'experience' => 220, 'avatar' => null, 'external_id' => null, 'external_auth' => null],
            ['name' => 'prova', 'email' => 'prova@sapalomera.cat', 'password' => bcrypt('prova'), 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now(), 'role' => 0, 'coins' => 1000000, 'experience' => 10000, 'avatar' => null, 'external_id' => null, 'external_auth' => null],
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
