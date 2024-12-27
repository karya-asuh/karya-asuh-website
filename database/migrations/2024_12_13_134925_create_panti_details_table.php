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
            CREATE TABLE `panti_details` (
                `panti_id` char(39) NOT NULL,
                `fund` int(19) UNSIGNED NOT NULL DEFAULT 0,
                `location` varchar(255) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            )
        ');

        // Alter the table to add keys
        DB::statement('
            ALTER TABLE `panti_details`
                ADD UNIQUE KEY `panti_id` (`panti_id`);
        ');
    }

    public function down()
    {
        Schema::dropIfExists('panti_details');
    }
};
