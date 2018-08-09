<?php

/**
 * Class UserBundleDataBaseService
 * Persistence service for User data.
 */
class UserBundleDataBaseService {
    private $dataBase;
    private $userAccessObj;

    public function __construct($dataBase) {
        $this->dataBase = $dataBase;
        $this->userAccessObj = new UserAccessConfig();
    }

    public function getPagesCount($action) {
        $isFired = $action == 'deletedUserList' ? 1 : 0;

        $query = "
            SELECT COUNT(*) 
            FROM `users`
            WHERE `isFired` = '{$isFired}'";
        $countRows = $this->dataBase->query($query)->fetch_array();

        $countRowsOnPage = (new Config())->getCountUsersOnPage();
        $countPages = ceil($countRows['0'] / $countRowsOnPage);

        return $countPages;
    }

    public function getList($userRequest, $currentPage, $action) {
        $userAccess = $this->userAccessObj->getListAccess();
        $isFired = $action == 'deletedUserList' ? 1 : 0;

        // prepare page data
        $countRowsOnPage = (new Config())->getCountUsersOnPage();

        // prepare current page
        $firstRowOnOpenPage = ($currentPage - 1) * $countRowsOnPage;
        $currentRow = 1;

        $query = "
            SELECT *
            FROM `users`
            WHERE `isFired` = '{$isFired}'";

        if ($userRequest['sorting']) {
            // Get user access flag for sorting by required field
            $sortingIsEnabled = @$userAccess[$userRequest['sorting']];
            if ($sortingIsEnabled) {
                $query .= " ORDER BY ".$userRequest['sorting'].($userRequest['direction'] == '1' ? " DESC" : "");
            }
        }
        // paginated selection
        $query .= " LIMIT {$countRowsOnPage} OFFSET {$firstRowOnOpenPage}";

        $resultTable = $this->dataBase->query($query);

        $response = array();
        $i = 0;
        while ($res = $resultTable->fetch_array()) {
            $userRow = array();
            foreach ($userAccess as $field => $accessFlag) {
                if (($accessFlag) && ($field != 'otherId')) {
                    $userRow += array($field => $res[$field]);
                }
            }
            $response += array($i => $userRow);
            $i++;
        }
        return $response;
    }

    public function getUser($id, $userAccess) {
        $query = "
            SELECT *, privilege+0
            FROM users
            WHERE id = '{$id}'";
        $response = array();
        $userData = $this->dataBase->query($query)->fetch_array();
        if (!$userData) {
            return false;
        }
        // Display all permitted fields with their values except the password field
        // which should be displayed empty
        foreach ($userAccess as $field => $accessFlag) {
            if (($accessFlag) && ($field != 'otherId')) {
                if ($field != 'password') {
                    $response += array($field => $userData[$field]);
                }
                else {
                    $response += array('password' => "");
                    $response += array('passwordRepeat' => "");
                }
            }
        }
        return $response;
    }

    public function addUser($user) {
        $query = "
            INSERT INTO `users` (";

        // The whole query should look like the following string:
        // "INSERT INTO `users` (`id`, `name`) VALUES ('007', 'James Bond')"
        //
        // 'keys' represents the second part of request which looks like
        // "id, name) VALUES ("
        //
        // 'values' represents the third part of request which looks like
        // "'007', 'James Bond')"
        $keys = "";
        $values = "";

        $firstIteration = true;
        foreach ($user as $key => $val) {
            if (!$firstIteration) {
                $keys .= ", ";
                $values .= ", ";
            }
            $keys .= "`{$key}`";
            $values .= "'{$val['value']}'";
            $firstIteration = false;
        }
        $keys .= ") VALUES (";
        $values .= ")";
        $query .= $keys . $values;

        // Return if the request succeeded or not
        return (bool)@$this->dataBase->query($query);
    }


    public function editUser($id, $user) {
        $query = "
        UPDATE `users`
        SET ";
        $firstIteration = true;
        foreach ($user as $key => $val) {
            if (($id > 1) || ($key != 'privilege')) {
                if (!$firstIteration) {
                    $query .= ", ";
                }
                $query .= "`{$key}` ='{$val['value']}'";
                $firstIteration = false;
            }
        }
        $query .= " WHERE `id` = '{$id}'";

        // Return if the request succeeded or not
        return (bool)@$this->dataBase->query($query);
    }

    public function deleteUser($id, $value) {
        // Prevent the root user deletion
        if ($id == 1) {
            return false;
        }
        $query = "
            UPDATE `users`
            SET `isFired` ='{$value}'
            WHERE `id` = '{$id}'";

        // Return if the request succeeded or not
        return (bool)@$this->dataBase->query($query);
    }

    public function getIsFired($id) {
        $query = "
            SELECT `isFired`
            FROM `users`
            WHERE  `id` = '{$id}'";
        if (@$isFired = $this->dataBase->query($query)->fetch_array()){
            return $isFired['isFired'];
        }
        else {
            return false;
        }
    }

    public function getPrivilege() {
        $query = "
            SELECT column_type 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE table_name=\"users\" 
            AND column_name=\"privilege\"";
        $type = $this->dataBase->query($query)->fetch_array();
        preg_match("/^enum\(\'(.*)\'\)$/", $type[0], $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }
}