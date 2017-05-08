<?php
function query($conn, $sql) {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all();
    return $rows;
}

function get_table($conn, $tbl) {
    $rows = query($conn, "SELECT * FROM {$tbl}");
    return $rows;
}