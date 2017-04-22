<?php
function get_cnn_news() {
    $data = url_get_contents('http://rss.cnn.com/rss/money_latest.rss');
    $xml = new SimpleXMLElement($data);
    $cols = ["Story / Link", "WC" ,"Description", "Published", "Count", "+", "-", "N/A"];
    $table = open_table($cols);
    foreach( $xml->channel->item as $item ){
        $table .= "<tr><td><a href='{$item->link}'>" . $item->title . "</a></td>";
        $table .= "<td><a href='../words.php?s={$item->link}'>" . 'WC' . "</a></td>";
        $table .= "<td>{$item->description}</td>";
        $table .= "<td>{$item->pubDate}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>";
    }
    $table .= close_table();

    return $table;
}