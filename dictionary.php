<?php
require_once("env.php");
require_once("functions.php");
check_search_params($_GET);
$conn = db($dbr); // New DB Connection

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$row = lookup_word($conn, $_GET['s']);
require_once('include/top.php');
print_search_form('dict');
print_definition($row);