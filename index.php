<?php
session_start();
include_once("config/Config.php");
include_once("config/UserAccessConfig.php");
include_once("services/SqlStringFormatter.php");
include_once("services/DataBaseConnector.php");
include_once("views/IndexTemplate.php");


class main {
    private $render = array(
        'head' => '',
        'title' => '',
        'contain' => ''
    );

    public function __construct() {
        $dataBase = (new DataBaseConnector())->connect();
        if ($dataBase) {
            switch(@$_GET['action']) {
                case 'userList':
                case 'addUser':
                case 'showUser':
                case 'editUser':
                case 'deleteUser':
                case 'changeUserPassword':
                case 'deletedUserList':
                    require_once "userBundle/UserBundleController.php";
                    $controller = new UserBundleController($dataBase, $_GET['action']);
                    $this->render = $controller->render();
                    break;
                case 'authorization':
                case 'exit':
                    require_once "authorizationBundle/AuthorizationBundleController.php";
                    $controller = new AuthorizationBundleController($dataBase, $_GET['action']);
                    $this->render = $controller->render();
                    break;
                default:
                    $this->render = array(
                        'head' => '',
                        'title' => 'index page',
                        'contain' => '<br />index page<br />'
                    );
                    break;
            }
        }
        else {
            echo "database access denied!";
        }
    }

    public function render() {
        return (new IndexTemplate())->render($this->render);
    }
}

echo (new main())->render();
