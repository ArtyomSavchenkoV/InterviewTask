<?php

/*
 * This class validate a data for insert or update to DataBase.
 * "Constructor" method gets data in a vector array.
 * The key of this array means name of the field in the Database, and value contains the data for validation.
 *
 * "getValidationResultArray" method return the table array, where row name is DataBase's field name, 
 * and columns are: 
 * value - contains the data (maybe corrected in validation process)
 * description - contains description about if the data was verified successfully or not. (This text can be shown in the certain forms)
 *
 * "isValid" method returns the validation result.
 */
class UserBundleValidationService {
    private $isValid = false;
    private $validationResultArray = array();

    public function __construct($userData) {
        $this->validationResultArray = array();
        $this->isValid = true;

        // Database fields parameters describe the requirements for input data.
        $fieldsParameters = (new Config())->getUserDatabaseFieldsParameters();

        foreach ($userData as $key => $userRequest) {
            $description = '';

            switch($key) {
                case 'login':
                    if ((strlen($userRequest) < $fieldsParameters['login']['minSize'])
                        || (strlen($userRequest) > $fieldsParameters['login']['maxSize'])) {
                        $this->isValid = false;
                        $description .= "Your login must be ".
                            $fieldsParameters['login']['minSize'].
                            " - ".
                            $fieldsParameters['login']['maxSize']." characters long. ";
                    }
                    break;
                case 'password':
                    if ((strlen($userRequest) < $fieldsParameters['password']['minSize'])
                        || (strlen($userRequest) > $fieldsParameters['password']['maxSize'])) {
                        $this->isValid = false;
                        $description .= "Your login must be ".
                            $fieldsParameters['password']['minSize'].
                            " - ".
                            $fieldsParameters['password']['maxSize']." characters long. ";
                    }
                    if ($userRequest != @$userData['passwordRepeat']) {
                        $this->isValid = false;
                        $description .= "The entered passwords do not match. ";
                    }
                    break;
                case 'name':
                    if ((strlen($userRequest) < $fieldsParameters['name']['minSize'])
                        || (strlen($userRequest) > $fieldsParameters['name']['maxSize'])) {
                        $this->isValid = false;
                        $description .= "Your name must be ".
                            $fieldsParameters['name']['minSize'].
                            " - ".
                            $fieldsParameters['name']['maxSize']." characters long. ";
                    }
                    break;
                case 'surname':
                    if ((strlen($userRequest) < $fieldsParameters['surname']['minSize'])
                        || (strlen($userRequest) > $fieldsParameters['surname']['maxSize'])) {
                        $this->isValid = false;
                        $description .= "Your surname must be ".
                            $fieldsParameters['surname']['minSize'].
                            " - ".
                            $fieldsParameters['surname']['maxSize']." characters long. ";
                    }
                    break;
                case 'burnDate':
                    break;
                case 'privilege':
                    break;
                case 'description':
                    if ((strlen($userRequest) < $fieldsParameters['description']['minSize'])
                        || (strlen($userRequest) > $fieldsParameters['description']['maxSize'])) {
                        $this->isValid = false;
                        $description .= "Your description must be " .
                            $fieldsParameters['description']['minSize'] .
                            " - " .
                            $fieldsParameters['description']['maxSize'] . " characters long. ";
                    }
                    break;
                case 'isFired':
                    break;
            }
            // generate the array for the user form
            if ($key != 'passwordRepeat') {
                $this->validationResultArray += array(
                    $key => array(
                        'value' => $userRequest,
                        'description' => $description
                    )
                );
            }
        }
    }

    public function isValid() {
        return $this->isValid;
    }

    public function getValidationResultArray() {
        return $this->validationResultArray;
    }
}