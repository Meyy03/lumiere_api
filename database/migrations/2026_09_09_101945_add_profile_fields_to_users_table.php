<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)
                ->nullable()
                ->after('email');

            $table->string('profile_image')
                ->nullable()
                ->after('phone');

            $table->text('shipping_address')
                ->nullable()
                ->after('profile_image');

            $table->string('province', 100)
                ->nullable()
                ->after('shipping_address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'profile_image',
                'shipping_address',
                'province',
            ]);
        });
    }
};