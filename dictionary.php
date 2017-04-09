<?php
require_once("env.php");
require_once("functions.php");
check_search_params($_GET);
if( isset($_POST['a'] ) ) { $redirect = 'dictionary.php?s=' . $_POST['a']; }
require_once('include/top.php');

$conn = db($dbr); // New DB Connection
print_logo();
print_search_form('dict');
if( isset($_GET['s']) ) {
    $rows = lookup_word($conn, $_GET['s']);
    if($rows) {
        print_definitions($rows);
    }
    else {
        print_not_found($_GET['s']);
    }
}

else if( isset($_POST['a']) && isset($_POST['t']) && isset($_POST['d'] ) ) {
    $r = [];
    $r[0] = $_POST['a'];
    $r[1] = $_POST['t'];
    $r[2] = $_POST['d'];
    if( isset($_POST['i']) ) {
        $r[3] = $_POST['i'];
        update_definition($conn,$r);
    }
    else { add_definition($conn,$r); }
}

else if( isset($_POST['c'] ) ) {
    delete_definition($conn, $_POST['c']);
}

else {
    print_random_definition($conn);
}