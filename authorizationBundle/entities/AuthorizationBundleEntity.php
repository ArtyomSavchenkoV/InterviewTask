<?php
include_once(dirname(__FILE__) . "/../services/AuthorizationBundleValidationService.php");
include_once(dirname(__FILE__) . "/../services/AuthorizationBundleDataBaseService.php");
include_once(dirname(__FILE__) . "/../views/AuthorizationFormFactory.php");
include_once(dirname(__FILE__) . "/../views/AuthorizationTemplate.php");

/**
 * Class AuthorizationBundleEntity
 * log in / log out class.
 * It can return ready HTML element with the form or response result authorization.
 * It can return result authorization for Ajax as well.
 */

class AuthorizationBundleEntity {
    private $dataBase;
    private $action;
    private $render = array(
            'head' => '',
            'title' => '',
            'contain' => ''
        );
    private $responseToAjax = '';

    public function __construct($dataBase, $action) {
        $this->dataBase = $dataBase;
        $this->action = $action;
    }

    public function authentication() {
        //prepare data for input to database
        $prepareData = new SqlStringFormatter();
        $authorizationData = array(
            'login' => $prepareData->prepareStringForSQL(@$_POST['login']),
            'password' => $prepareData->prepareStringForSQL(@$_POST['password'])
        );

        // validate Data
        $validationService = new AuthorizationBundleValidationService($authorizationData);

        // select branch from validated result
        if ($validationService->isValid()) {
            // request to DataBase
            $DataBaseService = new AuthorizationBundleDataBaseService($this->dataBase);
            if ($userData = $DataBaseService->authenticate($authorizationData)) {
                $_SESSION['name'] = $userData['name'];
                $_SESSION['surname'] = $userData['surname'];
                $_SESSION['privilegeIndex'] = $userData['privilege+0'];
                $_SESSION['privilege'] = $userData['privilege'];
                $_SESSION['id'] = $userData['id'];
                //render authorization result
                $this->render = (new AuthorizationTemplate())->authorizationSuccessfulRender();
                $this->responseToAjax = "Authorization complete";
            }
            // create form from validation result array or response to Ajax
            else {
                $formElementsArray = $validationService->getValidationResultArray();
                $form = new AuthorizationFormFactory($formElementsArray, $this->action);
                $this->render = $form->render();
                $this->render['contain'] = "Wrong user name or password<br />\n".$this->render['contain'];
                $this->responseToAjax = "Wrong user name or password";
            }
        }
        else {
            // create the form
            $formElementsArray = $validationService->getValidationResultArray();
            $form = new AuthorizationFormFactory($formElementsArray, $this->action);
            $this->render = $form->render();
            $this->responseToAjax = "Validation error";
        }
    }

    public function logOut() {
        session_destroy();
        $this->render = (new AuthorizationTemplate())->logOutRender();
    }

    public function render() {
        return $this->render;
    }

    public function responseToAjax () {
        return $this->responseToAjax;
    }
}