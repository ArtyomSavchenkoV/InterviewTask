<?php


class UserFormTemplate {
    public function formRender($elementId, $action, $userId, $method, $contain) {
        $element = "";
        if ($userId > 0) {
            $element .= "
                <a href='index.php?action=showUser&id={$userId}' class='button'>Show User</a>";
        }

        $element .= " <form ".
            (empty($elementId) ? "" : "id='{$elementId}' ").
            "action='{$action}' method='{$method}'>\n
            {$contain}\n
            
            </form>
            <a href='' class='button' id='ajaxSubmit'>Submit</a>
            ";
        return $element;
    }

    public function label($elementId, $name, $title, $value, $description, $maxSize) {
        $element = "
            <br />Enter: {$title} <br />
            
            <input ".
                (empty($elementId) ? "" : "id='{$elementId}' ").
                "type='text' name='{$name}' value='{$value}' maxlength='{$maxSize}'
            >
            
            <br />
            {$description}
        ";
        return $element;
    }

    public function passwordLabel($elementClass, $name, $name2, $title, $description, $maxSize) {
        $element = "
            <br />Enter: {$title} <br />
            
            <input ".
                (empty($elementClass) ? "" : "class='{$elementClass}' ").
                "type='password' name='{$name}' maxlength='{$maxSize}'
            >
            <br />\n
            password repeat:
            <br /> 
            <input ".
                (empty($elementClass) ? "" : "class='{$elementClass}' ").
                "type='password' name='{$name2}' maxlength='{$maxSize}'
            >
            <br />\n
            {$description}\n
        ";
        return $element;
    }

    public function select($elementId, $name, $title, $select, $value, $description) {
        $element = "
            <br />Select: {$title} <br />            
            <select name=\"{$name}\">";

        foreach ($select as $key => $val) {
            $element .= "<option value=\"".($key+1)."\" ".($val == $value ? "selected> *" : ">")."{$val}</option>";
        }

        $element .= "</select>
            <br />
            {$description}";
        return $element;
    }

    public function date($elementId, $name, $title, $value, $description) {
        $element = "
            <br />Enter: {$title} <br />
            
            <input ".
            (empty($elementId) ? "" : "id='{$elementId}' ").
            "type='date' name='{$name}' value='{$value}'
            >
            
            <br />{$description}
        ";
        return $element;
    }

    public function textArea($elementId, $name, $title, $value, $description, $maxSize) {
        $element = "
            <br />Enter: {$title} <br />
            
            <textarea ".
            (empty($elementId) ? "" : "id='{$elementId}' ").
            "type='text' name='{$name}'>".
            $value
            ."</textarea>
            <br />{$description}
        ";
        return $element;
    }
}