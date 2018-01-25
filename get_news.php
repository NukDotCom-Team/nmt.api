<?php
require "simple_html_dom.php";
function replace_substr($str, $what, $with){
    $i = 1;
    while (($offset = strpos($str, $what)) !== false) {
        $str = substr_replace($str, $with, $offset, strlen($what));
    }
    return $str;
}
$output = file_get_contents("http://www.nmt.edu.ru/");
