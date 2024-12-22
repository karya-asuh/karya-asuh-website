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
            CREATE TABLE `transactions` (
                `transaction_id` char(39) NOT NULL,
                `panti_id` char(39) NOT NULL,
                `donor_id` char(39) NOT NULL,
                `creation_id` char(39) NOT NULL,
                `price` int(19) UNSIGNED NOT NULL,
                `status` enum("On Payment","Success","Failed") NOT NULL DEFAULT "On Payment",
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            )
        ');

        // Alter the table to add keys
        DB::statement('
            ALTER TABLE `transactions`
                ADD PRIMARY KEY (`transaction_id`),
                ADD KEY `transactions_ibfk_2` (`donor_id`),
                ADD KEY `transactions_ibfk_3` (`creation_id`),
                ADD KEY `transactions_ibfk_1` (`panti_id`);
        ');
        
        // Create trigger for after update
        DB::statement('
            CREATE TRIGGER `set_transaction_after_update` AFTER UPDATE ON `transactions` FOR EACH ROW BEGIN
                IF EXISTS (
                    SELECT 1 
                    FROM users 
                    WHERE user_id = OLD.panti_id AND role = "panti" && NEW.status = "Success"
                ) THEN
                    UPDATE panti_details SET panti_details.fund = panti_details.fund + OLD.price
                    WHERE panti_details.panti_id = OLD.panti_id;
                END IF;
            END
        ');

        // Create trigger for before insert
        DB::statement('
            CREATE TRIGGER `set_transaction_before_insert` BEFORE INSERT ON `transactions` FOR EACH ROW BEGIN
                -- Fetch and assign the panti_id based on creation_id
                SELECT panti_id
                INTO @panti_id
                FROM creations
                WHERE creation_id = NEW.creation_id
                LIMIT 1;

                -- Assign the fetched panti_id to NEW.panti_id
                SET NEW.panti_id = @panti_id;

                -- Check if price is higher than min_price for creations
                IF NOT EXISTS (
                    SELECT 1 FROM creations WHERE creation_id = NEW.creation_id AND min_price <= NEW.price
                ) THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Invalid price: price must be \t\tequal more than min_price";
                END IF;
            END
        ');

        // Create trigger for before update
        DB::statement('
            CREATE TRIGGER `set_transaction_before_update` BEFORE UPDATE ON `transactions` FOR EACH ROW BEGIN
                IF OLD.status = "Success" THEN
                    SIGNAL SQLSTATE "45000" 
                    SET MESSAGE_TEXT = "Cannot modify a transaction with status Success";
                END IF;
            END
        ');
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
