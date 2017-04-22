<?php
require_once("functions.php");
require_once("../functions.php");
$title = "News Bot";
$table = get_cnn_news();
?>

<!Doctype html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.2/build/pure-min.css" integrity="sha384-UQiGfs9ICog+LwheBSRCt1o5cbyKIHbwjWscjemyBMT9YCUMZffs6UqUTd0hObXD" crossorigin="anonymous">
    <style>
        th {color: white; background-color: royalblue}
        tr:nth-child(even) {background: #EEE}
        tr:nth-child(odd) {background: #FFF}
    </style>
</head>
<?= $table ?>
</html>