<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\User;
use Illuminate\Support\Facades\DB;

// Swap name and email
$users = User::all();
foreach ($users as $user) {
    if (str_contains($user->name, '@')) { // If name contains @, it means it's definitely an email
        $temp = $user->name;
        $user->name = $user->email;
        $user->email = $temp;
        $user->save();
        echo "Swapped for user: {$user->user_id}\n";
    }
}

// Fix Stored Procedure AddUser
DB::unprepared("DROP PROCEDURE IF EXISTS `AddUser`;");
DB::unprepared("
CREATE PROCEDURE `AddUser`(IN `p_name` VARCHAR(100), IN `p_email` VARCHAR(100), IN `p_pass` VARCHAR(100), IN `p_role_id` INT)
BEGIN
    DECLARE new_id VARCHAR(10);
    DECLARE last_num INT;

    SELECT IFNULL(MAX(CAST(SUBSTRING(user_id, 3) AS UNSIGNED)), 0)
    INTO last_num
    FROM users;

    SET new_id = CONCAT('T-', LPAD(last_num + 1, 5, '0'));

    INSERT INTO users (user_id, name, email, pass, role_id)
    VALUES (new_id, p_name, p_email, p_pass, p_role_id);
END;
");

echo "Stored procedure AddUser updated successfully.\n";
