<?php
require_once("functions.php");
check_search_params($_GET);
require_once('include/top.php');
print_search_form();
?>