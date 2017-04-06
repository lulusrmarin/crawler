<?php
require_once("env.php");
require_once("functions.php");
check_search_params($_GET);
$conn = db($dbr); // New DB Connection

$row = lookup_word($conn, $_GET['s']);
require_once('include/top.php');
print_search_form('dict');
print_definition($row);