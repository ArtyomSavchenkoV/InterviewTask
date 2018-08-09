<?php
session_start();
include_once("config/Config.php");
include_once("config/UserAccessConfig.php");
include_once("services/SqlStringFormatter.php");
include_once("services/DataBaseConnector.php");
include_once("views/IndexTemplate.php");


$dataBase = (new DataBaseConnector())->connect();
if ($dataBase) {
    switch(@$_GET['action']) {
        case 'addUser':
        case 'editUser':
        case 'deleteUser':
        case 'changeUserPassword':
            require_once "userBundle/UserBundleController.php";
            $controller = new UserBundleController($dataBase, $_GET['action']);
            echo $controller->responseToAjax();
            break;
        case 'authorization':
        case 'exit':
            require_once "authorizationBundle/AuthorizationBundleController.php";
            $controller = new AuthorizationBundleController($dataBase, $_GET['action']);
            echo $controller->responseToAjax();
            break;
        default:
            echo "incorrect request";
            break;
    }
}
else {
    echo "database access denied!";
}