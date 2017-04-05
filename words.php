<?php
require_once("functions.php");
check_search_params($_GET);
require_once('include/top.php');
print_search_form('words');

if( isset($_GET['s']) ) {
    $cols = ["Word","Count",""];

    $html = url_get_contents($_GET['s']);
    $text = strip_tags($html);
    $words = explode(" ",$text);
    $merge = merge_array($words);
    arsort($merge);
    $max = reset($merge);

    echo open_table($cols);
    foreach($merge as $k => $v) {
        echo print_word_rows($k, $v, $max);
    }
    echo close_table();
}