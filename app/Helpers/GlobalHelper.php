<?php

if (!function_exists('getSecretToken')) {
    function getSecretToken() : string
    {
        return '7|ddVwAWCcbmI9TrUIwnSJAqO7K7DJY6ypsX5Fq5pvad7907ac';
    }
}


/** Set Sidebar Active */
if (!function_exists("setSidebarActive")) {
    function setSidebarActive($route) {
        if (is_array($route)) {
            foreach ($route as $r) {
                if (request()->routeIs($r)) {
                    return 'active';
                }
            }
        }
    }
}
