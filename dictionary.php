<?php
require_once("env.php");
require_once("functions.php");
check_search_params($_GET);
require_once('include/top.php');

$conn = db($dbr); // New DB Connection
print_search_form('dict');
if( isset($_GET['s']) ) {
    $rows = lookup_word($conn, $_GET['s']);
    print_definitions($rows);
}