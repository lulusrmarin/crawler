<?php
$path = "/word_fold/";
$path = $_SERVER["DOCUMENT_ROOT"] . $path;
require_once( $path . "env.php");
require_once( $path . "library/Zend/Dom/Query.php");
require_once( $path . "library/Zend/Dom/DOMXPath.php");
require_once( $path . "library/Zend/Dom/NodeList.php");
require_once( $path . "library/Zend/Dom/Document/Query.php");
?>

<!DOCTYPE HTML>
<head>
    <link rel="stylesheet" href="style.css">
    <?= ($title ? $title : NULL) ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="functions.js"></script>
    <?= ( isset($redirect) ? '<meta http-equiv="refresh" content="1;url=' . $redirect . '" />' : '' ); ?>
    <style>
    <?= ($style ? $style : NULL) ?>
    </style>
</head>
<body>
<?php print_header_nav() ?>