<?php
require_once("env.php");
require_once("functions.php");
$conn = new mysqli($dbr[0], $dbr[1], $dbr[2], $dbr[3]); // New DB Connection

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if ( isset( $_GET['s'] ) ) {
    // var_dump($_GET['word']);
    // /sigh
    $stmt = $conn->prepare("SELECT * FROM entries WHERE word = ?");
    $stmt->bind_param('s', $_GET['s'] );
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
}

require_once('include/top.php');
?>
<h1>Dictionary</h1>
<div id="search">
    <form method="get">
            <input id="search_form" type="search" name="s" placeholder=" <?= ( isset( $_GET['s'] ) ? "Word: " .  $_GET['s'] : 'Enter A Word' ) ?> "/>
    </form>
</div>

<div id='def'>
    <word><?= $row[0] ?></word>
    <word_type><?= $row[1] ?></word_type>
    <definition><?= $row[2] ?></definition>
</div>