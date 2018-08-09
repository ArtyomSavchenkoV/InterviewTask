<?php
/**
 * Class AuthorizationBundleValidationService
 * This class is intended to perform validation of the authentication inputs.
 * It also provides the messages in the case of validation fails.
 */

class AuthorizationBundleValidationService {

    private $isValid = false;
    private $validationResultArray = array();

    public function __construct(array $userData) {
        $this->validationResultArray = array();
        $this->isValid = true;

        // Database fields parameters describe the requirements for input data.
        $fieldsParameters = (new Config())->getUserDatabaseFieldsParameters();

        foreach ($userData as $key => $val) {
            $description = '';
            $value = $val;
            //check input data
            switch($key) {
                case 'login':
                    if ((strlen($val) < $fieldsParameters['login']['minSize'])
                        || (strlen($val) > $fieldsParameters['login']['maxSize'])) {
                        // invalid data processing
                        $this->isValid = false;
                        // this description will be displayed next to the input field
                        $description .= "Your login must be ".
                            $fieldsParameters['login']['minSize'].
                            " - ".
                            $fieldsParameters['login']['maxSize']." characters long. ";
                    }
                    break;
                case 'password':
                    if ((strlen($val) < $fieldsParameters['password']['minSize'])
                        || (strlen($val) > $fieldsParameters['password']['maxSize'])) {
                        // invalid data processing
                        $this->isValid = false;
                        // this description will be displayed next to the input field
                        $description .= "Your password must be ".
                            $fieldsParameters['password']['minSize'].
                            " - ".
                            $fieldsParameters['password']['maxSize']." characters long. ";
                    }
                    break;
            }

            // generate the array for the authorization form
            $this->validationResultArray += array(
                $key => array(
                    'value' => $value,
                    'description' => $description
                )
            );
        }
    }

    public function isValid()
    {
        return $this->isValid;
    }

    public function getValidationResultArray()
    {
        return $this->validationResultArray;
    }
}