<?php

namespace WHMCS\Module\Addon\Clientslogged;

use WHMCS\Database\Capsule;

require_once __DIR__ . '/../../../../init.php';
  

class Helper {

    // Loggedin Data Table
    function getLoggedinDataTable($start, $length, $search = '') {

        $query = Capsule::table('mod_clients_loggedin')
            ->select('clientid', 'email', 'ip_address', 'login_time', 'logout_time', 'time_spend', 'current_status');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                    ->orWhere('ip_address', 'like', "%$search%")
                    ->orWhere('login_time', 'like', "%$search%")
                    ->orWhere('logout_time', 'like', "%$search%")
                    ->orWhere('current_status', 'like', "%$search%");
            });
        }

        $activity = $query->skip($start)->take($length)->get();

        $data = [];

        foreach ($activity as $client) {

            $user = Capsule::table('tblclients')->where('id', $client->clientid)->first();

            if($client->current_status == "Login") {
                $status = "<p style='color: green;'>Login</p>";
            } else {
                $status = "<p style='color: red;'>Logout</p>";
            }
            
            // Check if user exists or not
            if ($user) {
                $data[] = [
                    'firstname' => "<a href='clientssummary.php?userid={$user->id}'>{$user->firstname}</a>",
                    'lastname' => "<a href='clientssummary.php?userid={$user->id}'>{$user->lastname}</a>",
                    'email' => $client->email,
                    'ip_address' => $client->ip_address,
                    'login' => $client->login_time,
                    'logout' => $client->logout_time,
                    'time' => $client->time_spend,
                    'status' => $status,
                ];
            }
        }

        return $data;

    }

    function getLoggedinCount($search = '') {

        $query = Capsule::table('mod_clients_loggedin')
            ->select('clientid', 'email', 'ip_address', 'login_time', 'logout_time', 'time_spend', 'current_status');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                    ->orWhere('ip_address', 'like', "%$search%")
                    ->orWhere('login_time', 'like', "%$search%")
                    ->orWhere('logout_time', 'like', "%$search%")
                    ->orWhere('current_status', 'like', "%$search%");
            });
        }

        return $query->count();

    }

    // Login Attempts Data Table
    function getAttemptsDataTable($start, $length, $search = '') {

        $query = Capsule::table('mod_login_attempts')
            ->select('email', 'attempts', 'ip_address', 'last_attempt', 'status');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                    ->orWhere('attempts', 'like', "%$search%")
                    ->orWhere('ip_address', 'like', "%$search%")
                    ->orWhere('last_attempt', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%");
            });
        }

        $activity = $query->skip($start)->take($length)->get();

        $data = [];

        foreach ($activity as $client) {

            $user = Capsule::table('tblclients')->where('email', $client->email)->first();
            if($user->status == "Active") {
                $disabled = '';
            } else {
                $disabled = 'disabled';
            }
            
            // Check if user exists or not
            if ($user) {
                $data[] = [
                    'firstname' => "<a href='clientssummary.php?userid={$user->id}'>{$user->firstname}</a>",
                    'lastname' => "<a href='clientssummary.php?userid={$user->id}'>{$user->lastname}</a>",
                    'email' => $client->email,
                    'attempts' => $client->attempts,
                    'ip_address' => $client->ip_address,
                    'last_attempt' => $client->last_attempt,
                    'status' => $client->status,
                    'action' => "<a href='#' class='btn btn-danger btn-sm' {$disabled} data-clientId='{$user->id}'> <i class='fa fa-ban'></i> Block User</a>",
                ];
            }
        }

        return $data;

    }

    function getAttemptsCount($search = '') {

        $query = Capsule::table('mod_login_attempts')
            ->select('email', 'attempts', 'ip_address', 'last_attempt', 'status');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                    ->orWhere('attempts', 'like', "%$search%")
                    ->orWhere('ip_address', 'like', "%$search%")
                    ->orWhere('last_attempt', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%");
            });
        }

        return $query->count();

    }
}
