<?php


class AuthorizationFormElementsTemplate {
    public function formRender($elementId, $action, $method, $contain) {
        $element = "
            <form ".
            (empty($elementId) ? "" : "id='{$elementId}' ").
            "action='{$action}' method='{$method}'>
            
            {$contain}
            
            </form>
            <a href='#' class='button' id='ajaxSubmit'>Submit</a>
            <div class='results'></div>";
        return $element;
    }

    public function label($elementId, $name, $title, $value, $description, $maxSize) {
        $element = "
            <br />Enter: {$title} <br />
            
            <input ".
            (empty($elementId) ? "" : "id='{$elementId}' ").
            "type='text' name='{$name}' value='{$value}' maxlength='$maxSize'
            >
            
            <br />{$description}<br />";
        return $element;
    }

    public function passwordLabel($elementClass, $name, $title, $description, $maxSize) {
        $element = "
            <br />Enter: {$title} <br />
            
            <input ".
            (empty($elementClass) ? "" : "class='{$elementClass}' ").
            "type='password' name='{$name}' maxlength='$maxSize'
            >
            
            <br />{$description}<br />";
        return $element;
    }
}