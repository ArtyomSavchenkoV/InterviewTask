<?php
include_once(dirname(__FILE__) . "/../services/UserBundleDataBaseService.php");
include_once(dirname(__FILE__) . "/UserBundleUserRequestCreator.php");
include_once(dirname(__FILE__) . "/../views/templates/UserBundleTemplate.php");

/**
 * Class UserBundleEntity
 * This class provides methods for proceeding different client actions with user bundle data:
 * user data validation and persistence in database service on the one side
 * and prepare view elements on the other side.
 * It also proceeds problem situations.
 */
class UserBundleEntity {
    private $render = array(
        'head' => '',
        'title' => '',
        'contain' => ''
    );
    private $responseToAjax = '';
    private $dataBase;
    private $action;
    private $userAccessObj;

    public function __construct($dataBase, $action) {
        $this->dataBase = $dataBase;
        $this->action = $action;
        $this->userAccessObj = new UserAccessConfig();
    }

    public function listUserAction() {
        $this->render['title'] = "User list";

        $userRequest = (new UserBundleUserRequestCreator())->listRequest($this->action);

        if ($userRequest) {
            $dataBase = new UserBundleDataBaseService($this->dataBase);
            $pageCount = $dataBase->getPagesCount($this->action);
            $openPage = $userRequest['page'];

            if ($openPage < 1) {
                $openPage = 1;
            }
            elseif ($openPage > $pageCount) {
                $openPage = $pageCount;
            }
            $dataBaseResponse = $dataBase->getList($userRequest, $openPage, $this->action);

            include_once (dirname(__FILE__)."/../views/UserBundleListView.php");

            $this->render = (new UserBundleListView(
                $this->render, 'index.php', $this->action,
                $dataBaseResponse, $userRequest, $pageCount, $openPage))->render();

            $this->render['title'] = "User list";
        }
        else {
            $this->render['contain'] .= 'access denied';
            $this->render['title'] = "User list";
        }
    }

    public function showUserAction() {
        $this->render['title'] = "User details";

        $userRequest = (new UserBundleUserRequestCreator())->showRequest();

        if ($userRequest) {
            include_once(dirname(__FILE__) . "/../views/UserBundleShowView.php");
            $dataBase = new UserBundleDataBaseService($this->dataBase);

            $dataBaseResponse = $dataBase->getUser($userRequest, $this->userAccessObj->getShowAccess());
            if ($dataBaseResponse) {
                $this->render = (new UserBundleShowView($this->render, $dataBaseResponse, $userRequest))->render();
            }
            else {
                $this->render['contain'] .= 'user not found';
            }
        }
        else {
            $this->render['contain'] .= 'access denied';
        }
    }

    public function editUserAction() {
        $this->render['title'] = "User editing";
        $userRequest = (new UserBundleUserRequestCreator())->editRequest();
        if ($userRequest) {
            include_once(dirname(__FILE__) . "/../services/UserBundleValidationService.php");
            $userId = @intval($_GET['id']);
            $validationService = new UserBundleValidationService($userRequest);

            // if the user request contains at least one value the request exists
            // otherwise we need to pick the data from database
            $requestExist = false;
            foreach ($userRequest as $key => $val) {
                if ($val) {
                    $requestExist = true;
                    break;
                }
            }

            if ($requestExist) {
                if ($validationService->isValid()) {
                    //set to Data Base
                    $dataBase = new UserBundleDataBaseService($this->dataBase);
                    $dataBaseResponse = $dataBase->editUser($userId,  $validationService->getValidationResultArray());
                    if ($dataBaseResponse) {
                        $this->responseToAjax = "updated";
                        $this->render['contain'] .= 'updated';
                    }
                    else {
                        $this->render['contain'] .= 'update failed';
                        $this->responseToAjax = "update failed";
                    }
                }
                else {
                    // prepare validation error message displaying via form or Ajax
                    include_once(dirname(__FILE__) . "/../views/CreateUserForm.php");
                    $formArray = $validationService->getValidationResultArray();
                    $this->render = (new CreateUserForm(
                        $this->render, $formArray, $this->action."&id=".$userId, $this->dataBase, $userId))
                        ->render();
                    $this->responseToAjax = "validation error";
                }
            }
            else {
                $dataResponse = (new UserBundleDataBaseService($this->dataBase))
                    ->getUser($userId, $this->userAccessObj->getEditAccess());
                $validationService = new UserBundleValidationService($dataResponse);
                include_once(dirname(__FILE__) . "/../views/CreateUserForm.php");
                $formArray = $validationService->getValidationResultArray();

                $this->render = (new CreateUserForm(
                    $this->render, $formArray, $this->action."&id=".$userId, $this->dataBase, $userId))
                    ->render();
                $this->responseToAjax = "validation error";
            }
        }
        else {
            $this->render['contain'] .= 'access denied';
            $this->responseToAjax = "access denied";
        }
    }

    public function changePasswordAction() {
        $userRequest = (new UserBundleUserRequestCreator())->changePasswordRequest();
        $this->render['title'] = "User change password";

        if($userRequest) {
            include_once(dirname(__FILE__) . "/../services/UserBundleValidationService.php");
            $userId = @intval($_GET['id']);
            $validationService = new UserBundleValidationService($userRequest);

            if ($validationService->isValid()) {
                //set to Data Base
                $dataBase = new UserBundleDataBaseService($this->dataBase);
                $dataBaseResponse = $dataBase->editUser($userId, $validationService->getValidationResultArray());
                if ($dataBaseResponse) {
                    $this->render['contain'] .= 'password was updated';
                    $this->responseToAjax = "password was updated";
                }
                else {
                    $this->render['contain'] .= 'password updating was failed';
                    $this->responseToAjax = "password updating was failed";
                }
            }
            else {
                include_once(dirname(__FILE__) . "/../views/CreateUserForm.php");
                $formArray = $validationService->getValidationResultArray();
                $this->render = (new CreateUserForm(
                    $this->render, $formArray, $this->action."&id=".$userId,
                    $this->dataBase, $userId))->render();
                $this->responseToAjax = "validation error";
            }
        }
        else {
            $this->render['contain'] .= 'access denied';
            $this->responseToAjax = "access denied";
        }
    }

    public function addUserAction() {
        $this->render['title'] = "New user";
        $userRequest = (new UserBundleUserRequestCreator())->addRequest();

        if ($userRequest) {
            include_once(dirname(__FILE__) . "/../services/UserBundleValidationService.php");
            $validationService = new UserBundleValidationService($userRequest);

            if ($validationService->isValid()) {
                //set to Data Base
                $dataBase = new UserBundleDataBaseService($this->dataBase);

                $dataBaseResponse = $dataBase->addUser($validationService->getValidationResultArray());

                if ($dataBaseResponse) {
                    $this->render['contain'] .= '';
                    $this->responseToAjax = "new user has been added";
                }
                else {
                    $this->render['contain'] .= 'failed';
                    $this->responseToAjax = "failed";
                }
            }
            else {
                include_once(dirname(__FILE__) . "/../views/CreateUserForm.php");
                $formArray = $validationService->getValidationResultArray();
                $this->render = (new CreateUserForm(
                    $this->render, $formArray, $this->action, $this->dataBase, ""))->render();
                $this->responseToAjax = "validation error";
            }
        }
        else {
            $this->render['contain'] .= 'access denied';
            $this->responseToAjax = "access denied!";
        }
    }

    public function deleteUserAction() {
        $this->render['title'] = "User delete/recover";
        $userRequest = (new UserBundleUserRequestCreator())->deleteRequest();
        if ($userRequest) {
            $dataBase = new UserBundleDataBaseService($this->dataBase);
            $isFired = $dataBase->getIsFired($userRequest);
            $dataBaseResponse = $dataBase->deleteUser($userRequest, ($isFired ? 0 : 1));
            if ($dataBaseResponse) {
                $this->render['contain'] .= 'successful';
                $this->responseToAjax = "successful";
            }
            else {
                $this->render['contain'] .= 'failed';
                $this->responseToAjax = "failed";
            }
        }
        else {
            $this->render['contain'] .= 'access denied';
            $this->responseToAjax = "access denied";
        }
    }

    public function render() {
        return (new UserBundleTemplate())->render($this->render);
    }

    public function responseToAjax() {
        return $this->responseToAjax;
    }
}