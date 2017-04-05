<?php require_once('include/top.php'); ?>
<div id="search">
    <form method="get">
        <input id="search_form" type="search" name="url" placeholder=" <?= ( isset( $_GET['url'] ) ? "Address: " .  $_GET['url'] : 'Enter A URL' ) ?> "/>
    </form>
</div>