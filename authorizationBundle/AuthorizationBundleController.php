<?php
include_once (dirname(__FILE__) . "/entities/AuthorizationBundleEntity.php");

/**
 * Class AuthorizationBundleController
 * This class accepts the authentication data and transmit it to authorisation bundle for validation and persistence.
 */
class AuthorizationBundleController {
    private $render = array(
        'head' => '',
        'title' => '',
        'contain' => ''
    );
    private $responseToAjax = '';

    public function __construct($dataBase, $action) {
        $entity = new AuthorizationBundleEntity($dataBase, $action);

        switch ($action) {
            case 'authorization':
                $entity->authentication();
                break;
            case 'exit':
                $entity->logOut();
                break;
        }
        $this->render = $entity->render();
        $this->responseToAjax = $entity->responseToAjax();
    }

    public function render() {
        return $this->render;
    }

    public function responseToAjax () {
        return $this->responseToAjax;
    }
}