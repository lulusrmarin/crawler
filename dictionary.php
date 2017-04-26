<?php
$time_start = microtime(true);

require_once("functions.php");
check_search_params($_GET);
if( isset($_POST['a'] ) ) { $redirect = 'dictionary.php?s=' . $_POST['a']; }
if( isset($_POST['val'] ) ) {
    $word = explode("-", $_POST['val'] );
    $redirect = 'dictionary.php?s=' . $word[0];
}

require_once('include/top.php');

$conn = db($dbr); // New DB Connection
print_logo();
print_search_form('dict');
if( isset($_GET['s']) ) {
    $rows = lookup_word($conn, $_GET['s']);
    if($rows) {
        print_definitions($rows);
    }
    elseif( strpos($_GET['s']," ") || $_GET['s'] == NULL ) {
        echo "<div id='def' style='text-align: center;'>Please search a valid word</div>";
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

else if( isset( $_POST['val'] ) ) {
    if( isset( $_POST['val'] ) )
        { $word = explode("-", $_POST['val'] ); }

    if( change_val($conn, $word[0], $word[1]) )
        echo "<div id='def'>You set the value of a word</div>";
}

else {
    print_random_definition($conn);
}

$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
echo $execution_time . " seconds to finish.";