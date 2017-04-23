<?php
$time_start = microtime(true);
require_once("../env.php");
require_once("functions.php");
require_once("../functions.php");
$conn = db($dbr);

$title = "News Bot";
$table = get_cnn_news($conn);
$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
echo $execution_time . " to finish.";
?>

<!Doctype html>
<head>
    <title><?= $title ?></title>
    <style>
        th {color: white; background-color: royalblue}
        tr:nth-child(even) {background: #EEE}
        tr:nth-child(odd) {background: #FFF}
    </style>
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.2/build/pure-min.css" integrity="sha384-UQiGfs9ICog+LwheBSRCt1o5cbyKIHbwjWscjemyBMT9YCUMZffs6UqUTd0hObXD" crossorigin="anonymous">
</head>
<?= $table ?>
</html>