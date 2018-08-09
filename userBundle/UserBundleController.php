<?php

/**
 * Class UserBundleController
 * This class accepts the user data and transmit it to user bundle for validation and persistence.
 */
class UserBundleController {
    private $render = array(
        'head' => '',
        'title' => '',
        'contain' => ''
    );
    private $responseToAjax = '';

    public function __construct($dataBase, $action) {
        include_once("entities/UserBundleEntity.php");
        $entity = new UserBundleEntity($dataBase, $action);
        switch ($_GET['action']) {
            case "userList":
            case "deletedUserList":
                $entity->listUserAction();
                break;
            case "addUser":
                $entity->addUserAction();
                break;
            case "editUser":
                $entity->editUserAction();
                break;
            case "changeUserPassword":
                $entity->changePasswordAction();
                break;
            case "showUser":
                $entity->showUserAction();
                break;
            case "deleteUser":
                $entity->deleteUserAction();
                break;
            default:
                echo "default";
                break;
        }
        $this->render = $entity->render();
        $this->responseToAjax = $entity->responseToAjax();
    }

    public function render() {
        return $this->render;
    }

    public function responseToAjax() {
        return $this->responseToAjax;
    }
}
