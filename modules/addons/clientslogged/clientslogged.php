<?php

if(!defined("WHMCS")) {
    die("This file cannot be accessed directly!");
}

use WHMCS\Module\Addon\ClientsLogged\Admin\AdminDispatcher;
use WHMCS\Module\Addon\ClientsLogged\Helper;

use WHMCS\Database\Capsule;


function clientslogged_config() {
    return [
        'name' => "Client's logging Activities",
        'description' => "In this module you will find the client's logged in activities",
        'author' => 'WGS',
        'language' => 'english',
        'version' => '1.0',
        'fields' => [
            'module_des' => [
                'FriendlyName' => 'Description',
                'Type' => 'textarea',
                'Rows' => '3',
                'Cols' => '60',
            ],
            'delete_database' => [
                'FriendlyName' => 'Delete Database',
                'Type' => 'yesno',
                'Description' => "Delete module database when deactivate the module?",
            ]
        ]
    ];
}

function clientslogged_activate() {
    try {
        // Create table to store client's login activity
        if (!Capsule::schema()->hasTable('mod_clients_loggedin')) {
            Capsule::schema()->create('mod_clients_loggedin', function ($table) {
                $table->increments('id');
                $table->integer('clientid');
                $table->string('email');
                $table->string('ip_address')->nullable();
                $table->string('login_time')->nullable();
                $table->string('logout_time')->nullable();
                $table->string('time_spend')->nullable();
                $table->string('current_status');
            });
        }

        // Create table to store failed login attempts
        if (!Capsule::schema()->hasTable('mod_login_attempts')) {
            Capsule::schema()->create('mod_login_attempts', function ($table) {
                $table->increments('id');
                $table->string('email');
                $table->integer('attempts')->default(1);
                $table->string('ip_address')->nullable();
                $table->string('last_attempt');
                $table->string('status');
            });
        }

        return [
            'status' => 'success',
            'description' => 'Module activated successfully',
        ];
    } catch (\Exception $e) {
        return [
            'status' => "error",
            'description' => 'Unable to activate module: ' . $e->getMessage(),
        ];
    }
}


function clientslogged_deactivate() {
    try {
        return [
            'status' => 'success',
            'description' => 'Module deactivated successfully',
        ];
    } catch (\Exception $e) {
        return [
            "status" => "error",
            "description" => "Unable to drop : {$e->getMessage()}"
        ];
    }
}


function clientslogged_output($vars)
{
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'loggedin';

    $dispatcher = new AdminDispatcher();
    $dispatcher->dispatch($action, $vars);
}