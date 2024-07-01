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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id(); // PK
            $table->string('street', 200)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('country', 100)->nullable(false);
            $table->string('postal_code', 10)->nullable();
            $table->unsignedBigInteger('contact_id')->nullable(false); // FK
            $table->timestamps();

            $table->foreign('contact_id')->on('contacts')->references('id');

            /**
             * show create table
             *
             * CREATE TABLE `addresses` (
             * `id` bigint unsigned NOT NULL AUTO_INCREMENT,
             * `street` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
             * `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
             * `province` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
             * `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
             * `contact_id` bigint unsigned NOT NULL,
             * `created_at` timestamp NULL DEFAULT NULL,
             * `updated_at` timestamp NULL DEFAULT NULL,
             * PRIMARY KEY (`id`),
             * KEY `addresses_contact_id_foreign` (`contact_id`),
             * CONSTRAINT `addresses_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`)
             * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
             */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
