<?php
/**
 * Class UserAccessConfig
 *
 * This configuration entity describes the variety permission models for the different roles:
 * '1' that means the 'administrator' role,
 * '2' that means the 'user' role.
 *
 * Certain functions of this class form different access schema objects: so-called 'accessArray'.
 * These objects represents sets of flags corresponding to different presentation fields.
 * For this flags there are two variants:
 * '0' that means the field must not be displayed,
 * '1' that means the field must be displayed.
 */

class UserAccessConfig {
    public function showDeleteUsers () {
        switch (@$_SESSION['privilegeIndex']) {
            case '1':
                $showDeleteUsers = true;
                return $showDeleteUsers;
                break;
            case '2':
                $showDeleteUsers = false;
                return $showDeleteUsers;
                break;
            default:
                return false;
        }
    }

    public function getListAccess() {
        switch (@$_SESSION['privilegeIndex']) {
            case '1':
                $accessArray = array (
                    'otherId' => 1,
                    'id' => 1,
                    'login' => 1,
                    'password' => 0,
                    'name' => 1,
                    'surname' => 1,
                    'burnDate' => 0,
                    'description' => 0,
                    'privilege' => 1,
                    'isFired' => 0
                );
                return $accessArray;
                break;
            case '2':
                $accessArray = array (
                    'otherId' => 1,
                    'id' => 1,
                    'login' => 0,
                    'password' => 0,
                    'name' => 1,
                    'surname' => 1,
                    'burnDate' => 0,
                    'description' => 0,
                    'privilege' => 0,
                    'isFired' => 0
                );
                return $accessArray;
                break;
            default:
                return false;
        }
    }

    public function getShowAccess() {
        switch (@$_SESSION['privilegeIndex']) {
            case '1':
                $accessArray = array (
                    'otherId' => 1,
                    'login' => 1,
                    'password' => 0,
                    'name' => 1,
                    'surname' => 1,
                    'burnDate' => 1,
                    'description' => 1,
                    'privilege' => 1,
                    'isFired' => 1
                );
                return $accessArray;
                break;
            case '2':
                $accessArray = array (
                    'otherId' => 1,
                    'login' => 0,
                    'password' => 0,
                    'name' => 1,
                    'surname' => 1,
                    'burnDate' => 1,
                    'description' => 1,
                    'privilege' => 0,
                    'isFired' => 0
                );
                return $accessArray;
                break;
            default:
                return false;
        }
    }

    public function getEditAccess() {
        switch (@$_SESSION['privilegeIndex']) {
            case '1':
                $accessArray = array (
                    'otherId' => 1,
                    'login' => 1,
                    'password' => 0,
                    'name' => 1,
                    'surname' => 1,
                    'burnDate' => 1,
                    'description' => 1,
                    'privilege' => 1,
                    'isFired' => 0
                );
                return $accessArray;
                break;
            case '2':
                $accessArray = array (
                    'otherId' => 0,
                    'login' => 0,
                    'password' => 0,
                    'name' => 1,
                    'surname' => 1,
                    'burnDate' => 1,
                    'description' => 1,
                    'privilege' => 0,
                    'isFired' => 0
                );
                return $accessArray;
                break;
            default:
                return false;
        }
    }

    public function getChangePasswordAccess() {
        switch (@$_SESSION['privilegeIndex']) {
            case '1':
                $accessArray = array (
                    'otherId' => 1,
                    'login' => 0,
                    'password' => 1,
                    'name' => 0,
                    'surname' => 0,
                    'burnDate' => 0,
                    'description' => 0,
                    'privilege' => 0,
                    'isFired' => 0
                );
                return $accessArray;
                break;
            case '2':
                $accessArray = array (
                    'otherId' => 0,
                    'login' => 0,
                    'password' => 1,
                    'name' => 0,
                    'surname' => 0,
                    'burnDate' => 0,
                    'description' => 0,
                    'privilege' => 0,
                    'isFired' => 0
                );
                return $accessArray;
                break;
            default:
                return false;
        }
    }

    public function getAddAccess() {
        switch (@$_SESSION['privilegeIndex']) {
            case '1':
                $accessArray = array (
                    'otherId' => 1,
                    'login' => 1,
                    'password' => 1,
                    'name' => 1,
                    'surname' => 1,
                    'burnDate' => 1,
                    'description' => 1,
                    'privilege' => 1,
                    'isFired' => 0
                );
                return $accessArray;
                break;
            case '2':
                return false;
                break;
            default:
                return false;
        }
    }

    public function getDeleteAccess() {
        switch (@$_SESSION['privilegeIndex']) {
            case '1':
                $accessArray = array (
                    'otherId' => 1,
                    'login' => 0,
                    'password' => 0,
                    'name' => 0,
                    'surname' => 0,
                    'burnDate' => 0,
                    'privilege' => 0,
                    'isFired' => 1
                );
                return $accessArray;
                break;
            case '2':
                return false;
                break;
            default:
                return false;
        }
    }
}