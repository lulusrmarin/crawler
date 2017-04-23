<?php
function get_cnn_news($conn) {
    $data = url_get_contents('http://rss.cnn.com/rss/money_latest.rss');
    $xml = new SimpleXMLElement($data);
    $cols = ["Story / Link", "WC" ,"Description", "Published", "Count", "+", "-", "N/A"];
    $table = open_table($cols);
    foreach( $xml->channel->item as $item ){
        $merge = get_text_from_url($item->link);
        $sorted = sort_words($conn, $merge);
        $word_count = sizeof($sorted['words']);

        $table .= "<tr><td><a href='{$item->link}'>" . $item->title . "</a></td>";
        $table .= "<td><a href='../words.php?s={$item->link}'>" . 'WC' . "</a></td>";
        $table .= "<td>{$item->description}</td>";
        $table .= "<td>{$item->pubDate}</td>
            <td>{$word_count}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>";
    }
    $table .= close_table();

    return $table;
}