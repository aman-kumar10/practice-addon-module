<?php

namespace WHMCS\Module\Addon\ClientsLogged\Admin;
use WHMCS\Module\Addon\ClientsLogged\Admin\Controller;

class AdminDispatcher
{
    public function dispatch($action, $parameters)
    {
        $controller = new Controller($parameters);
        if (is_callable([$controller, $action])) {
            $controller->$action();
        } else {
            echo '<p>Invalid action requested. Please go back and try again.</p>';
        }
    }
}