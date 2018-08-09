<?php

class userBundleTemplate {
    public function render($render) {
        $render['head'] = "<link rel=\"stylesheet\" href=\"img/userModuleStyles.css\" type=\"text/css\">\n" . $render['head'];
        $render['title'] .= "";
        $render['contain'] = "<div id='userModule'>{$render['contain']}</div>";

        return $render;
    }
}