<?php

class DataBaseConnector {
    private $dataBaseLogin      = "sibersAdmin";
    private $dataBasePassword   = "124";
    private $dataBaseName       = "sibersDB";


    private function tryConnect () {
        $mysqli = new mysqli('localhost', $this->dataBaseLogin, $this->dataBasePassword, $this->dataBaseName);

        if (mysqli_connect_errno()) {
            throw new Exception("cant connect to Database! ");
        }
        $mysqli->set_charset("utf8");
        return $mysqli;
    }

    public function connect() {
        try {
            return  $this->tryConnect ();
        } catch (Exception $e) {
            echo 'Exception: ' . $e->getMessage();
        }
    }
}