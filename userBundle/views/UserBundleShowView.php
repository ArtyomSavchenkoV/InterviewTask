<?php
include_once(dirname(__FILE__) ."/templates/UserBundleShowTemplate.php");


class UserBundleShowView {
    private $render = array();

    public function __construct($render, $userData, $id) {
        $this->render = $render;
        $fieldsParameters = (new Config())->getUserDatabaseFieldsParameters();
        $template = new UserBundleShowTemplate();
        $contain = "";

        $contain .= $template->button("", "List user", "index.php?action=userList");

        foreach ($userData as $key => $val) {
            $elementType = @$fieldsParameters [$key]['elementType'];
            if ($key == 'isFired') {
                if ($val == 1) {
                    $contain .= $template->string("", "Fired.", '');
                }
            }
            else {
                switch ($elementType) {
                    case 'label':
                        $contain .= $template->string("", $fieldsParameters [$key]['title'], $val);
                        break;
                    case 'select':
                        $contain .= $template->string("", $fieldsParameters [$key]['title'], $val);
                        break;
                    case 'date':
                        $contain .= $template->string("", $fieldsParameters [$key]['title'], $val);
                        break;
                    case 'textArea':
                        $contain .= $template->string("", $fieldsParameters [$key]['title'], $val);
                        break;
                }
            }
        }

        // Generate action buttons
        $userAccess = new UserAccessConfig();
        if (($id == $_SESSION['id']) || ($userAccess->getEditAccess()['otherId'])) {
            $contain .= $template->button("", "Edit user", "index.php?action=editUser&id=".$id);
            $contain .= $template->button("", "change password", "index.php?action=changeUserPassword&id=".$id);
        }
        if ($userAccess->getDeleteAccess()) {
            if($userData['isFired']) {
                $contain .= $template->button("", "Restore user", "index.php?action=deleteUser&id=".$id);
            }
            else {
                $contain .= $template->button("", "Delete user", "index.php?action=deleteUser&id=".$id);
            }
        }
        $this->render['contain'] = $contain;
    }

    public function render() {
        return $this->render;
    }
}