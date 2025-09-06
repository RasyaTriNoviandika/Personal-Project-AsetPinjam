<?php

if (!function_exists('checkRole')) {
    function checkRole($role)
    {
        return auth()->check() && auth()->user()->role === $role;
    }
}
