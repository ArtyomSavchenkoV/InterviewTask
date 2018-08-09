<?php

/**
 * Class UserBundleUserRequestCreator
 * This class is intended for client api-request proceeding and translating them to requests for user bundle.
 */
class UserBundleUserRequestCreator {
    public function listRequest($action) {
        $userAccessConfig = new UserAccessConfig();
        $userAccess = $userAccessConfig->getListAccess();
        $showDeleteUserAccess = $userAccessConfig->showDeleteUsers();
        $prepareData = new SqlStringFormatter();
        if (!$userAccess) return false;

        if ($action == 'deletedUserList') {
            if (!$showDeleteUserAccess) {
                return false;
            }
        }

        $requestArray = array(
            'page' => @intval($_GET['page']),
            'sorting' => $prepareData->prepareStringForSQL(@$_GET['sorting']),
            'direction' => @intval($_GET['direction'])
        );
        return $requestArray;
    }

    public function showRequest() {
        $userAccess = (new UserAccessConfig())->getShowAccess();
        $idUser =  @intval($_GET['id']);
        if (!$userAccess) {
            return false;
        }
        if (($idUser == $_SESSION['id']) || ($userAccess['otherId'])) {
            return $idUser;
        }

        return false;
    }

    public function editRequest() {
        $prepareData = new SqlStringFormatter();
        $userAccess = (new UserAccessConfig())->getEditAccess();
        $idUser =  @intval($_GET['id']);
        if (!$userAccess) {
            return false;
        }
        if (($idUser != $_SESSION['id']) && (!$userAccess['otherId'])) {
            return false;
        }

        $request = array();
        foreach ($userAccess as $key => $val) {
            if (($val) && ($key != 'otherId')) {
                $request += array($key => $prepareData->prepareStringForSQL(@$_POST[$key]));

            }
        }
        return $request;
    }

    public function changePasswordRequest() {
        $prepareData = new SqlStringFormatter();
        $userAccess = (new UserAccessConfig())->getChangePasswordAccess();
        $idUser =  @intval($_GET['id']);
        if (!$userAccess) {
            return false;
        }
        if (($idUser != $_SESSION['id']) && (!$userAccess['otherId'])) {
            return false;
        }

        $request = array();

        $request += array('password' => $prepareData->prepareStringForSQL(@$_POST['password']));
        $request += array('passwordRepeat' => $prepareData->prepareStringForSQL(@$_POST['passwordRepeat']));

        return $request;
    }

    public function addRequest() {
        $prepareData = new SqlStringFormatter();
        $userAccess = (new UserAccessConfig())->getAddAccess();

        if(!$userAccess) return false;

        $request = array();
        foreach ($userAccess as $key => $val) {
            if (($val) && ($key != 'otherId')) {
                $request += array($key => $prepareData->prepareStringForSQL(@$_POST[$key]));
                if ($key = 'password') {
                    $request += array('passwordRepeat' => $prepareData->prepareStringForSQL(@$_POST['passwordRepeat']));
                }
            }
        }
        return $request;
    }

    public function deleteRequest() {
        $userAccess = (new UserAccessConfig())->getDeleteAccess();
        $idUser =  @intval($_GET['id']);
        if (!$userAccess) {
            return false;
        }
        if ($idUser < 2) return false;
        if ($userAccess['otherId']) {
            return $idUser;
        }
        else {
            return false;
        }
    }
}