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

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
