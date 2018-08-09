<?php

class Config {
    private $countUsersOnPage = 2;

    private $UserDatabaseFieldsParameters = array(
        'id' => array(
            'title' => 'ID'
        ),
        'login' => array(            // name database field
            'title' => 'login',
            'minSize' => 4,
            'maxSize' => 16,
            'elementType' => 'label' // type element for form or view
        ),
        'password' => array(
            'title' => 'password',
            'minSize' => 4,
            'maxSize' => 16,
            'elementType' => 'password'
        ),
        'passwordRepeat' => array(
            'title' => 'password repeat',
        ),
        'name' => array(
            'title' => 'name',
            'minSize' => 2,
            'maxSize' => 32,
            'elementType' => 'label'
        ),
        'surname' => array(
            'title' => 'surname',
            'minSize' => 2,
            'maxSize' => 32,
            'elementType' => 'label'
        ),
        'burnDate' => array(
            'title' => 'date of birth',
            'minSize' => 4,
            'maxSize' => 16,
            'elementType' => 'date'
        ),
        'description' => array(
            'title' => 'description',
            'minSize' => 0,
            'maxSize' => 500,
            'elementType' => 'textArea'
        ),
        'privilege' => array(
            'title' => 'access status',
            'elementType' => 'select',
            'minSize' => 1,
            'maxSize' => 1
        ),
        'isFired' => array(
            'title' => 'is fired',
            'elementType' => 'select',
        )
    );

    /**
     * @return array
     */
    public function getUserDatabaseFieldsParameters()
    {
        return $this->UserDatabaseFieldsParameters;
    }

    /**
     * @return int
     */
    public function getCountUsersOnPage()
    {
        return $this->countUsersOnPage;
    }
}
