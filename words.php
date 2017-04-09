<?php
use function var_dump as vd;
use function print_r as pr;

require_once("functions.php");
check_search_params($_GET);
require_once('include/top.php');
print_logo();
print_search_form('words');
$conn = db($dbr);

if( isset($_GET['s']) ) {
    $html = url_get_contents($_GET['s']);
    $text = strip_tags($html);
    //vd($text);
    $words = explode(" ",$text);
    $merge = merge_array($words);
    arsort($merge);

    $sorted = sort_words($conn, $merge);

    // Columns for table
    $cols = ["Word","Count",""];
    print_word_table('Words',$cols,$sorted['words']);
    br();
    $cols = ["Word","","Count",""];
    print_word_table('Non-Words',$cols,$sorted['non_words'],true);
}