<?php


class UserBundleShowTemplate {
    public function string($elementId, $title, $content) {
        $element = "
            <div>
                <b>{$title}</b>
                <div" . (empty($elementId) ? "" : " id='{$elementId}'") . ">
                {$content}
                </div>
            </div>
        ";
        return $element;
    }

    public function button($elementId, $title, $action) {
        $element = "
            <a href='{$action}'" . (empty($elementId) ? "" : " id='{$elementId}'") . " class=\"button\">
                {$title}
                </a>
        ";
        return $element;
    }
}