<?php
use function var_dump as vd;
use function print_r as pr;
require_once("functions.php");
check_search_params($_GET);
require_once('include/top.php');
print_logo();
print_search_form('words');
$GLOBALS['db'] = $conn = db($dbr);

if( isset($_GET['s']) ) {
    $s = $_GET['s'];
    $merge = get_text_from_url($s);
    $sorted = sort_words($conn, $merge);
    // Columns for table
    $cols = ["Word","Count",""];
    print_word_table('Words', $cols, $sorted['words'], NULL, $s);
    br();
    $cols = ["Word","","Count",""];
    print_word_table('Non-Words', $cols, $sorted['non_words'],true);
}