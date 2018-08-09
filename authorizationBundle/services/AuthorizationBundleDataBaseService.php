<?php
/**
 * Class AuthorizationBundleDataBaseService
 * Persistence service for authorization data.
 */

class AuthorizationBundleDataBaseService {
    private $dataBase;

    public function __construct($dataBase) {
        $this->dataBase = $dataBase;
    }

    public function authenticate($request) {
        $query = "
        SELECT
            *, privilege+0
        FROM 
            users
        WHERE login = '{$request['login']}' AND isFired = '0'
        ";

        $queryResult = $this->dataBase->query($query);
        if ($data = @$queryResult->fetch_array()){
            if ($data['password'] == $request['password']) {
                return $data;
            }
        }
        return false;
    }
}