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
            CREATE TABLE `creations` (
                `creation_id` char(39) NOT NULL,
                `panti_id` char(39) NOT NULL,
                `name` varchar(100) NOT NULL,
                `desc` text DEFAULT NULL,
                `min_price` int(11) NOT NULL,
                `type` enum("video","image") NOT NULL DEFAULT "image",
                `creation_file` varchar(42) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            )
        ');

        // Alter the table to add keys
        DB::statement('
            ALTER TABLE `creations`
                ADD PRIMARY KEY (`creation_id`),
                ADD KEY `panti_id` (`panti_id`);
                ADD UNIQUE KEY `unique_creation_file` (`creation_file`);
        ');

        // Create trigger for before insert
        DB::statement('
            CREATE TRIGGER `set_creation_before_insert` BEFORE INSERT ON `creations` FOR EACH ROW BEGIN
                -- Automatically set the creation_id with a prefix if not provided
                SET NEW.creation_id = CONCAT("CR-", UUID());
                -- Automatically reject inserted creation that inserted by non panti
                IF NOT EXISTS (
                    SELECT 1 
                    FROM users 
                    WHERE user_id = NEW.panti_id AND role = "panti"
                ) THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Invalid panti_id: Must reference a user with role \"panti\"";
                END IF;
            END
        ');
    }

    public function down()
    {
        Schema::dropIfExists('creations');
    }
};
