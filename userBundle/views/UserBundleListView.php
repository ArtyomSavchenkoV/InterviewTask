<?php
include_once(dirname(__FILE__) ."/templates/UserBundleListTemplate.php");

class UserBundleListView {
    private $render = array();

    private $head = '<link rel="stylesheet" href="img/userListStyles.css" type="text/css">
        <link rel="stylesheet" href="img/pagePanelStyles.css" type="text/css">';

    public function __construct($render, $path, $action, $DataBaseResponse, $userRequest, $pageCount, $openPage) {
        $template = new UserBundleListTemplate();
        $config = (new Config())->getUserDatabaseFieldsParameters();
        $userAccessConfig = new UserAccessConfig();
        $userAccess = $userAccessConfig->getAddAccess();
        $showDeletedUsers = $userAccessConfig->showDeleteUsers();
        $this->render = $render;
        $this->render['head'] = $this->head;

        // generate pages
        $this->render['contain'] .= $template->pagePanel(
            $path.'?action='.$action, $userRequest, $pageCount, $openPage);

        // generate user list
        $table = "";
        $row = "";

        if ($pageCount > 0) {
            //generate table header
            foreach ($DataBaseResponse['0'] as $key => $val) {
                $title = $config[$key]['title'];
                $direction = $path.'?action='.$action."&sorting=".$key;
                if ($userRequest['sorting'] == $key) {
                    if ($userRequest['direction'] == 1) {
                        $title = "<b>&#8593;".$title."&#8593;</b>";
                        $direction = $direction."&direction=0";
                    }
                    else {
                        $title = "<b>&#8595;".$title."&#8595;</b>";
                        $direction = $direction."&direction=1";
                    }
                }
                $row .= $template->tableHead($title, $direction);
            }
            $table .= $template->row($row,"");

            //generate table content
            foreach ($DataBaseResponse as $userRowKey => $userRow) {
                $row = "";
                foreach ($userRow as $key => $val) {
                    $row .= $template->cell($val);
                }
                $table .= $template->row($row, "index.php?action=showUser&id=".$userRow['id']);
            }
            $table = $template->table($table);
        }
        else {
            $table .= 'list is empty';
        }

        // generate buttons
        if ($showDeletedUsers) {
            if ($action == 'deletedUserList') {
                $table .= "<a href=\"".$path."?action=userList\" class=\"button\">user list</a>";
            }
            else {
                $table .= "<a href=\"".$path."?action=deletedUserList\" class=\"button\">deleted user list</a>";
                if($userAccess) {
                    $table = "<a href=\"index.php?action=addUser\" class=\"button\">Add User</a>" . $table;
                }
            }
        }
        $this->render['contain'] .= $table;
    }

    public function render() {
        return $this->render;
    }
}