<?php

if(!defined("WHMCS")) {
    die("This file cannot be accessed directly!");
}

use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\Clientslogged\Helper;

add_hook('ClientLoginShare', 1, function($vars) {
    
    try {
        $attemptRecord = Capsule::table('mod_login_attempts')->where('email', $vars['username'])->first();
        $attempts = $attemptRecord->attempts ?? 0;
        $ip_add = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $userExists = Capsule::table('tblclients')->where('email', $vars['username'])->first();
        if($userExists) {
            $exist = Capsule::table('mod_login_attempts')->where('email', $vars['username'])->first();
            if($exist) {
                Capsule::table('mod_login_attempts')->where('email', $vars['username'])->update([
                    'attempts' => $attempts + 1,
                    'ip_address' => $ip_add,
                    'last_attempt' => date("Y-m-d H:i:s"),
                ]);
            } else {
                Capsule::table('mod_login_attempts')->insert([
                    'email' => $vars['username'],
                    'attempts' => $attempts+1,
                    'ip_address' => $ip_add,
                    'last_attempt' => date("Y-m-d H:i:s"),
                    'status' => $userExists->status
                ]);
            }
        }
    } catch(Exception $e) {
        logActivity("Error logging login attempt: " . $e->getMessage());
    }

});

add_hook('UserLogin', 1, function($vars) {
    
    try {
        $client = $vars['user'];
        $ip_add = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

        if($client) {
            $exist = Capsule::table('mod_clients_loggedin')->where('clientid', $client->id)->first();
            if($exist) {
                Capsule::table('mod_clients_loggedin')->where('clientid', $client->id)->update([
                    'ip_address' => $ip_add,
                    'login_time' => date("Y-m-d H:i:s"),
                    'logout_time' => "-",
                    'time_spend' => "-",
                    'current_status' => "Login"
                ]);
            } else {
                Capsule::table('mod_clients_loggedin')->insert([
                    'clientid' => $client->id,
                    'email' => $client->email,
                    'ip_address' => $ip_add,
                    'login_time' => date("Y-m-d H:i:s"),
                    'logout_time' => "-",
                    'time_spend' => "-",
                    'current_status' => "Login"
                ]);
            }
        }

    } catch(Exception $e) {
        logActivity("Error to insert logging activity: " . $e->getMessage());
    }

});


// add_hook('UserLogout', 1, function($vars) {
    
//     try {
//         $client = $vars['user'];
//         $ip_add = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

//         if($client) {

//             $exist = Capsule::table('mod_clients_loggedin')->where('clientid', $client->id)->first();

//             $login_time = $exist->login_time;
//             $logout_time = date("Y-m-d H:i:s");

//             $login = new DateTime($login_time);
//             $logout = new DateTime($logout_time);
//             $interval = $login->diff($logout);

//             if($exist) {
//                 Capsule::table('mod_clients_loggedin')->where('clientid', $client->id)->update([
//                     'ip_address' => $ip_add,
//                     'logout_time' => $logout_time,
//                     'time_spend' => $interval,
//                     'current_status' => "Logout"
//                 ]);
//             } else {
//                 Capsule::table('mod_clients_loggedin')->insert([
//                     'clientid' => $client->id,
//                     'email' => $client->email,
//                     'ip_address' => $ip_add,
//                     'login_time' => "-",
//                     'logout_time' => $logout_time,
//                     'time_spend' => "-",
//                     'current_status' => "Logout"
//                 ]);
//             }
//         }

//     } catch(Exception $e) {
//         logActivity("Error to insert logging activity: " . $e->getMessage());
//     }

// });




add_hook('UserLogout', 1, function($vars) {
    try {
        $client = $vars['user'];
        $ip_add = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

        if ($client) {
            $exist = Capsule::table('mod_clients_loggedin')
                ->where('clientid', $client->id)
                ->first();

            $logout_time = date("Y-m-d H:i:s");
            $time_spent = '-';

            if ($exist && $exist->login_time !== '-') {
                $login_time = $exist->login_time;

                $login = new DateTime($login_time);
                $logout = new DateTime($logout_time);
                $interval = $login->diff($logout);

                $time_spent = $interval->format('%H:%I:%S');
            }

            if ($exist) {
                Capsule::table('mod_clients_loggedin')
                    ->where('clientid', $client->id)
                    ->update([
                        'ip_address'      => $ip_add,
                        'logout_time'     => $logout_time,
                        'time_spend'      => $time_spent,
                        'current_status'  => 'Logout'
                    ]);
            } else {
                Capsule::table('mod_clients_loggedin')->insert([
                    'clientid'        => $client->id,
                    'email'           => $client->email,
                    'ip_address'      => $ip_add,
                    'login_time'      => '-',
                    'logout_time'     => $logout_time,
                    'time_spend'      => '-',
                    'current_status'  => 'Logout'
                ]);
            }
        }
    } catch (Exception $e) {
        logActivity("Error inserting logout activity: " . $e->getMessage());
    }
});

