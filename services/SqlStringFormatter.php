<?php


class SqlStringFormatter {
    public function prepareStringForSQL ($text) {
        $text = strip_tags($text);
        $text = htmlspecialchars($text);
        $text = addslashes($text);

        return $text;
    }
}