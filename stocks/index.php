<?php
require '../functions.php';
require 'misc.php';
require 'mysql.php';
require 'print.php';
require '../env.php';

CONST BUY = 1;
CONST SELL = 0;

$conn = db($dbr);
$ticker = ( isset($_GET['s']) ? $_GET[ 's'] : 'GOOG' );
execute_trade($conn, [ 'type' => SELL, 'symbol' => $ticker, 'currency' => 'USD', 'value' => 10.00]);

if( $_POST ) {
    if( $_POST['test'] ) {
        $data['state'] = get_current_state( $conn );
    }

    if( $_POST['history'] ) {
        $data['history']['trades'] = get_table( $conn, 'tralo' );
        $data['history']['account'] = get_table ($conn, 'stocks' );
    }

    if( $_POST['symbol'] ) {
        $url = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.quote%20where%20symbol%20%3D%20%22{$_POST['symbol']}%22&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
        $data['symbol'] = json_decode( url_get_contents($url) );
    }

    echo json_encode( $data );
    exit;
}
?>
<!DOCTYPE html>
<head>
<link rel="stylesheet" href="https://unpkg.com/purecss@0.6.2/build/pure-min.css" integrity="sha384-UQiGfs9ICog+LwheBSRCt1o5cbyKIHbwjWscjemyBMT9YCUMZffs6UqUTd0hObXD" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        div {
            border: solid 1px black;
        }
        table {
            width: 100%;
        }
        th {
            background-color: royalblue;
            color: white;
        }
    </style>
</head>
<body>
</body>
<script src="../js/page.js"></script>
</html>