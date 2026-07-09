<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('active_role')) {

    function active_role()
    {
        return session('active_role');
    }
}