<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create the table
        DB::statement('
            CREATE TABLE `users` (
                `user_id` char(39) NOT NULL,
                `username` varchar(100) NOT NULL,
                `name` varchar(100) NOT NULL,
                `email` varchar(100) NOT NULL,
                `password` char(72) NOT NULL,
                `role` enum("donor","panti","admin") NOT NULL DEFAULT "donor",
                `user_image` varchar(36) DEFAULT NULL,
                `remember_token` varchar(100) DEFAULT NULL,  -- Added remember_token column
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            )
        ');


        // Alter the table to add keys
        DB::statement('
            ALTER TABLE `users`
                ADD PRIMARY KEY (`user_id`),
                ADD UNIQUE KEY `username` (`username`)
        ');

        // Create trigger for before insert
        DB::statement('
            CREATE TRIGGER `set_user_before_insert` BEFORE INSERT ON `users` FOR EACH ROW
            BEGIN
                IF NEW.role = "donor" THEN
                    SET NEW.user_id = CONCAT("DO-", UUID());
                ELSEIF NEW.role = "panti" THEN
                    SET NEW.user_id = CONCAT("PA-", UUID());
                ELSEIF NEW.role = "admin" THEN
                    SET NEW.user_id = CONCAT("AD-", UUID());
                END IF;
            END
        ');

        // Create trigger for after insert
        DB::statement('
            CREATE TRIGGER `set_user_after_insert` AFTER INSERT ON `users` FOR EACH ROW
            BEGIN
                IF (NEW.role = "panti") THEN
                    INSERT INTO `panti_details` (`panti_id`) VALUES (NEW.user_id);
                END IF;
            END
        ');

        // Create sessions table using raw SQL
        DB::statement('
            CREATE TABLE `sessions` (
                `id` varchar(255) NOT NULL,
                `user_id` char(39) DEFAULT NULL,
                `ip_address` varchar(45) DEFAULT NULL,
                `user_agent` text DEFAULT NULL,
                `payload` longtext NOT NULL,
                `last_activity` int NOT NULL,
                PRIMARY KEY (`id`),
                KEY `sessions_user_id_index` (`user_id`),
                CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE ON DELETE CASCADE
            )
        ');    
    }

    public function down()
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
    }
};
