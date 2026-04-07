<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('has_role')) {
    function has_role($role) {
        if (!isset($_SESSION['roles']) || empty($_SESSION['roles'])) {
            return false;
        }
        $roles = array_map('trim', explode(',', $_SESSION['roles']));
        return in_array($role, $roles, true);
    }
}

if (!function_exists('can_manage')) {
    function can_manage($module) {
        if (!isset($_SESSION['user_type'])) {
            return false;
        }

        // Admin → tous les droits partout
        if ($_SESSION['user_type'] === 'admin') {
            return true;
        }

        // Utilisateur normal → on regarde uniquement ses rôles
        if ($_SESSION['user_type'] === 'user') {
            return has_role($module);
        }

        return false;
    }
}