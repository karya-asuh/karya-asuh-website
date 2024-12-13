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
            CREATE TABLE `withdraws` (
                `withdraw_id` char(39) NOT NULL,
                `panti_id` char(39) NOT NULL,
                `admin_id` char(39) DEFAULT NULL,
                `payout_fund` int(19) UNSIGNED NOT NULL,
                `detail` text DEFAULT NULL,
                `status` enum("Request For Withdraw","Withdrawn") NOT NULL DEFAULT "Request For Withdraw",
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            )
        ');

        // Alter the table to add keys
        DB::statement('
            ALTER TABLE `withdraws`
                ADD PRIMARY KEY (`withdraw_id`),
                ADD KEY `withdraws_ibfk_1` (`panti_id`),
                ADD KEY `withdraws_ibfk_2` (`admin_id`);
        ');

        // Create trigger for before insert
        DB::statement('
            CREATE TRIGGER `set_withdraw_before_insert` BEFORE INSERT ON `withdraws` FOR EACH ROW BEGIN
                -- Automatically set the withdraw_id with a prefix if not provided
                SET NEW.withdraw_id = CONCAT("WD-", UUID());
                
                -- Automatically reject inserted creation that is inserted by non-panti
                IF NOT EXISTS (
                    SELECT 1 
                    FROM users 
                    WHERE user_id = NEW.panti_id AND role = "panti"
                ) THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Invalid panti_id: Must reference a user with role \"panti\"";
                ELSE
                    UPDATE panti_details SET panti_details.fund = panti_details.fund - NEW.payout_fund
                    WHERE panti_details.panti_id = NEW.panti_id;
                END IF;
            END
        ');

        // Create trigger for before update
        DB::statement('
            CREATE TRIGGER `set_withdraw_before_update` BEFORE UPDATE ON `withdraws` FOR EACH ROW BEGIN
                IF OLD.status = "Withdrawn" THEN
                    SIGNAL SQLSTATE "45000" 
                    SET MESSAGE_TEXT = "Cannot modify a transaction with status Withdrawn";
                END IF;
                
                IF NEW.status = "Withdrawn" THEN
                    -- Verify that the user updating the data has the role "admin"
                    IF NOT EXISTS (
                        SELECT 1 
                        FROM users
                        WHERE user_id = NEW.admin_id AND role = "admin"
                    ) THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Only users with role \"admin\" can update the status to \"Withdrawn\"";
                    END IF;
                END IF;
            END
        ');
    }

    public function down()
    {
        Schema::dropIfExists('withdraws');
    }
};
