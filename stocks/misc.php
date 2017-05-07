<?php
function result_to_array( $rows ) {
    $r = [];
    foreach($rows as $row) {
        $r['state_id'] = $row[0];
        $r['cash'] = $row[1];
        $r['fee'] = $row[2];
        $r['stocks'] = $row[3];
        $r['value'] = $row[4];
        $r['timestamp'] = $row[5];
    }
    return $r;
}

function get_current_state( $conn ) {
    $row = query($conn,"SELECT * FROM stocks ORDER BY `timestamp` DESC LIMIT 1");
    $r = result_to_array( $row );
    //print_current_state($r);
    return $r;
}

function execute_trade($conn, $params) {
    $query = "INSERT INTO tralo 
        SET `type` = {$params[ 'type' ]}
        ,stock = '{$params[ 'symbol' ]}', currency='USD'
        ,`value`= 10.00
        ,`timestamp` = NOW()";
    $conn->query($query);
}