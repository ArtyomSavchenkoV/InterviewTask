<?php


class AuthorizationTemplate {
    public function authorizationSuccessfulRender() {
        $head = "";
        $title = "You are successfully logged in.";
        $contain = "
            <div>You are successfully logged in.</div>
            <a href = 'index.php' class=\"button\">to Index page</a>
            ";

        return array(
            'head' => $head,
            'title' => $title,
            'contain' => $contain
        );
    }

    public function logOutRender(){
        $head = "";
        $title = "You are logged out.";
        $contain = "
             <div>You are logged out.</div>
             <a href = 'index.php' class=\"button\">to Index page</a>
             ";

        return array(
            'head' => $head,
            'title' => $title,
            'contain' => $contain
        );
    }
}