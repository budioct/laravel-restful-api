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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id(); // primary key
            $table->string('first_name', 100)->nullable(false);
            $table->string('last_name', 100)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('phone', 20)->nullable();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id'); // foreign key

            /**
             * show create table
             *
             * CREATE TABLE `contacts` (
             * `id` bigint unsigned NOT NULL AUTO_INCREMENT,
             * `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `last_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
             * `email` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
             * `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
             * `user_id` bigint unsigned NOT NULL,
             * `created_at` timestamp NULL DEFAULT NULL,
             * `updated_at` timestamp NULL DEFAULT NULL,
             * PRIMARY KEY (`id`),
             * KEY `contacts_user_id_foreign` (`user_id`),
             * CONSTRAINT `contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
             * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
             */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
