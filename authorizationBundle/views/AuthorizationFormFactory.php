<?php
/**
 * Class AuthorizationFormFactory
 * This simple so-called factory is intended for authorisation form creation.
 */

class AuthorizationFormFactory {
    private $render = array(
        'head' => '<script type="text/javascript" src="img/ajaxRequestAuthorization.js"></script>',
        'title' => '',
        'contain' => ''
    );

    public function __construct(array $userData, $action) {
        include("AuthorizationFormElementsTemplate.php");

        $fieldsParameters = (new Config())->getUserDatabaseFieldsParameters();
        $form = new AuthorizationFormElementsTemplate();
        $formContain = "";

        foreach ($userData as $key => $val) {

            $formElement = $fieldsParameters [$key]['elementType'];
            switch ($formElement) {
                case 'label':
                    $formContain .= $form->label("",
                        $key,
                        $fieldsParameters [$key]['title'],
                        $val['value'],
                        $val['description'],
                        $fieldsParameters [$key]['maxSize']
                    );
                    break;
                case 'password':
                    $formContain .= $form->passwordLabel("",
                        $key,
                        $fieldsParameters [$key]['title'],
                        $val['description'],
                        $fieldsParameters [$key]['maxSize']
                    );
                    break;
            }
        }

        $this->render['contain'] = $form->formRender(
            "authorizationForm",
            "index.php?action={$action}",
            "POST", $formContain);
    }

    public function render() {
        $this->render['title'] = 'Authorization form';
        return $this->render;
    }
}