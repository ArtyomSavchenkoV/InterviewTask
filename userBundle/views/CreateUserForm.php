<?php
include(dirname(__FILE__) ."/templates/UserFormTemplate.php");
include(dirname(__FILE__) ."/templates/UserBundleShowTemplate.php");


class CreateUserForm {
    private $render = array();

    public function __construct($render, $userData, $action, $dataBase, $userId) {
        $this->render = $render;
        $scriptBegin = '
            <script type="text/javascript">               
                $(document).ready(function(){
                    $(\'#ajaxSubmit\').bind(\'click\', function(){
                        $.ajax({
                          type: \'POST\',
                          url: \'indexAjax.php?action='.$action.'\',
                          data: $(\'#userForm\').serialize()';
        $scriptDate = "";
        $scriptEnd = ',
                          success: function(data){
                            alert(data);
                          }
                        });
                    });
                });        
            </script>
            ';

        $fieldsParameters = (new Config())->getUserDatabaseFieldsParameters();
        $form = new UserFormTemplate();
        $formContain = "";
        foreach ($userData as $key => $val) {
            $formElement = @$fieldsParameters [$key]['elementType'];
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
                        $key."Repeat",
                        $fieldsParameters [$key]['title'],
                        $val['description'],
                        $fieldsParameters [$key]['maxSize']
                    );
                    break;
                case 'select':
                    $select = (new UserBundleDataBaseService($dataBase))->getPrivilege();
                    $formContain .= $form->select(
                        "",
                        $key,
                        $fieldsParameters [$key]['title'],
                        $select,
                        $val['value'],
                        $val['description']
                        );
                    break;
                case 'date':
                    $formContain .= $form->date($key,
                        $key,
                        $fieldsParameters [$key]['title'],
                        $val['value'],
                        $val['description']
                    );
                    $scriptDate .= '+"&'.$key.'="+(document.getElementById(\''.$key.'\').value)';
                    break;
                case 'textArea':
                    $formContain .= $form->textArea("",
                        $key,
                        $fieldsParameters [$key]['title'],
                        $val['value'],
                        $val['description'],
                        $fieldsParameters [$key]['maxSize']
                    );
                    break;
            }
        }
        $this->render['head'] .= $scriptBegin.$scriptDate.$scriptEnd;
        $this->render['contain'] = $form->formRender(
            "userForm", "index.php?action={$action}", $userId, "POST", $formContain);
    }

    public function render() {
        return $this->render;
    }
}