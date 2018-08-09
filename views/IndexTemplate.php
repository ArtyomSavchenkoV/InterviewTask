<?php

class IndexTemplate {
    public function render($render) {
    $render['head'] = "<link rel=\"stylesheet\" href=\"img/styles.css\" type=\"text/css\">\n
        <script type=\"text/javascript\" src=\"img/jquery_v_1-3-2.js\"></script>
        " . $render['head'];

    if (@$_SESSION['name']) {
        $authorizationPanel = @$_SESSION['surname'].' '.@$_SESSION['name']."<br /> \n".@$_SESSION['privilege'].
            "<br /><a href = \"index.php?action=exit\" class=\"button\">exit</a>";
    }
    else {
        $authorizationPanel = '<a href = "index.php?action=authorization" class="button">Authorization</a>';
    }

    $menu = '<a href = "index.php?action=userList" class="button">UserList</a>';

    $render['contain'] = "
        <!DOCTYPE html>
        <html>
            <head>
                {$render['head']}
                <title>{$render['title']}</title>                
            </head>            
            <body>
                <div id='contain'>
                    <div id='title'>{$render['title']}</div>
                    <div id='authorizationPanel'>
                        {$authorizationPanel}
                    </div>
                    <div id='menuPanel'>
                        {$menu}
                    </div>
                    <div id = 'contant'>
                        {$render['contain']}                    
                    </div>                    
                </div>
            </body>
        </html>            
        ";

        return $render['contain'];
    }
}