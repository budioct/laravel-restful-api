<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->nullable(false)->unique("users_username_unique");
            $table->string('password', 100)->nullable(false);
            $table->string('name', 100)->nullable(false);
            $table->string('token', 100)->nullable()->unique("users_token_unique");
            $table->timestamps();

            /**
             * show create table
             *
             * CREATE TABLE `users` (
             * `id` bigint unsigned NOT NULL AUTO_INCREMENT,
             * `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
             * `created_at` timestamp NULL DEFAULT NULL,
             * `updated_at` timestamp NULL DEFAULT NULL,
             * PRIMARY KEY (`id`),
             * UNIQUE KEY `users_username_unique` (`username`),
             * UNIQUE KEY `users_token_unique` (`token`)
             * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
             */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
