<?php
///// Print Functions
function print_array($r, $conn) {
    arsort($r);
    echo "<table><tr></tr><th>Word</th><th>Count</th><th>Type</th></tr>";
    foreach ($r as $word => $count) {
        $results = $conn->query("SELECT word, wordtype FROM entries WHERE word = '" . $word . "' AND wordtype != ''");
        echo $conn->error; // Echo error if necessary.

        $row = mysqli_fetch_object($results);
        $type = $row->wordtype;
        echo "<td><a href='dictionary.php?word=" . $word . "'>" . $word . "</a></td><td> " . $count . "</td><td>" . $type . "</td><tr/>";
    }
    echo "</table>";
}

function print_link_rows($r) {
    $s = "<tr>
            <td class='link_name'><a href='" . $r[0] . "'>" . implode( "</a>, <a href='" . $r[0] . "'>",$r[1] ) . "</a></td>
            <td class='count'>" . count($r[1]) . "</td><td><a href='?url=" . $r[0] . "'>" . $r[0] . "</a></td><td class='count'>" . $r[2] . "</td>
            <td class='line_graph'><div class='line-graph' style='width: " . ( round($r[2] / $r[3] * 100 ) ) . "%;'>&nbsp;</div></td>
          </tr>";
    return $s;
}

function print_word_rows($k,$v,$max,$button = false) {
    $s = "<tr>
            <td class='link_name'><a href='dictionary.php?s=" . urlencode($k) . "'>" . substr($k,0,100) .
        "</a></td>" . ( $button === true ? '<td id="nc_button">' . print_correction_button($k) . '</td>' : "" ) . "<td class='count'>" . $v . "</td>" .
        "<td class='line_graph'><div class='line-graph' style='width: " . ( round($v / $max * 100 ) ) . "%;'>&nbsp;</div></td>
          </tr>";
    return $s;
}

function print_word_table($name,$cols,$r,$button = false, $s = NULL) {
    $max = reset($r);

    echo "<div class='word-table'>" . "<h3 class='table_title'>" . $name . "</h3>";
    echo open_table($cols);
    foreach($r as $k => $v) {
        echo print_word_rows($k, $v, $max,$button);
        if($s) { log_word($GLOBALS['db'], [$s, $k, $v, $max ]); }
    }
    echo close_table();
    echo "</div>";
}

function print_correction_button($s) {
    $s = "<form action='dictionary.php'><button name='s' value='{$s}'>Is this incorrect?</button></form>";
    return $s;
}

function print_links($results) {
    $cols = ["Link Text","Txt Count", "Address", "Link Count"];
    $r = Array();
    foreach ($results as $result) {
        ;
        if (!isset($r[$result->getAttribute('href')]['count'])) {
            $r[$result->getAttribute('href')]['count'] = 0;
        }
        $r[$result->getAttribute('href')]['count']++;
        if (in_array($result->textContent, $r[$result->getAttribute('href')]['text'])) {
            continue;
        }
        $r[$result->getAttribute('href')]['text'][] = $result->textContent;
        //echo print_table_rows( [ $result->getAttribute('href'), $result->textContent ] );
    }

    //array_filter($r);
    array_sort_by_column($r, 'count', SORT_DESC);
    $k = key($r);
    $max_count = $r[$k]['count']; // Should probably be driving this with max(), take note if the sort changes

    echo "<div class='table'><h3>links</h3>" . open_table($cols);
    foreach ($r as $k => $v) {
        echo print_link_rows([$k, $v['text'], $v['count'],$max_count]);
    }
    echo close_table() . "</div>";
}

function print_search_form($page = NULL) {
    $terms = set_search_values($page);

    echo '<div id="search">
        <form method="get" action="' . $terms['action'] . '">
            <input id="search_form" type="search" name="s" placeholder="' . $terms["placeholder"] . '"/>';
    print_radio_buttions($page);
    echo '</form>
    </div>';
}

function print_radio_buttions($page) {
    echo"<div id=\"options\">
        <input id='rad-dict' type=\"radio\" name=\"st\" value=\"0\" " . ( $page == 'dict' ? "checked='checked'" : "" ) . " />Dictionary
        <input id='rad-words' type=\"radio\" name=\"st\" value=\"1\" " . ( $page == 'words' ? "checked='checked'" : "" ) . " />Words in Page
        <input id='rad-links' type=\"radio\" name=\"st\" value=\"2\" " . ( $page == 'links' ? "checked='checked'" : "" ) . " />Links in Page
    </div>";
}

function print_definitions($rows) {
    print_add_buttons();
    print_hidden_textbox( $rows[0][0] );
    $i = 0;

    echo "<div id='val'>Word is: " . "<span id=" . ($rows[0][4] == 0 ? "'neut'>Neutral" : NULL)
    . ($rows[0][4] == 1 ? "'pos'>Positive" : NULL)
    . ($rows[0][4] == 2 ? "'neg'>Negative" : NULL)
    . "</span></div>";

    foreach( $rows as $row ) {
        print_definition($row, $row[3]);
        //echo $i;
        $i++;
    }
    print_val_buttons( $row[0] );
}

function print_definition($row, $i = NULL) {
    echo "<div id='def'>";
    echo "<form action='dictionary.php' method='post'>";
    echo "<input id ='id{$i}' type='hidden' name='i' value='{$row[3]}' />";
    echo "<word id='word{$i}'>{$row[0]}</word>";
    echo "<input id='hid{$i}' type='hidden' value='{$row[0]}' />";
    echo "<word_type id='wt{$i}'>{$row[1]}</word_type>";
    echo "<definition id='def{$i}'>{$row[2]}</definition>";
    print_edit_buttons($i);
    echo "</form>";
    echo "</div>";
}

function print_random_definition($conn) {
    $all_words = lookup_all_words($conn);
    $i = rand(0, sizeof($all_words) );
    echo "<h2>Word of the day</h2>";
    $wod = $all_words[$i][0];
    $rows = lookup_word( $conn, $wod );
    print_definitions( $rows );
}

function print_add_buttons() {
    echo "<div id='buttons-add'>";
    button('Add A Definition','add-button');
    echo "</div>";
}

function print_edit_buttons($i) {
    echo "<div id='buttons-edit'>";
    href("<img src='images/icons/pencil.png'>","#", "replace_this({$i})",  'edit-button');
    href("<img src='images/icons/delete.png'>","#", "delete_this({$i})",  'delete-button');
    echo "</div>";
    echo "<button style='display: none' id='add-in-edit{$i}'>Enter your definition!</button>";
}

function print_hidden_textbox($s) {
    echo "<div id='add-div'>{$s}
                <form action='dictionary.php' method='post'>
                    <select name='t'>
                        <option value='n.'>n.</option>
                        <option value='v.'>v.</option>
                        <option value='adj.'>adj.</option>
                        <option value=''>???</option>
                    </select>
                    <textarea name='d' placeholder='Define This Word'></textarea>
                    <button name='a' value='{$s}'>Enter your definition!</button>
                </form>    
            </div>";
}

function print_not_found($s) {
    echo "<div id='def'><h2>{$s}</h2> is not in our dictionary.  If this is a valid word, please help us by adding it.</div>";
    print_add_buttons();
    print_hidden_textbox($s);
}

function print_logo() {
    echo "<h4>Tuple</h4>";
}

function print_header_nav() {
    define("CHAT_URL",'http://52.53.241.187:3000/');
    echo "<div id='header-nav'><a href='" . CHAT_URL . "'>Tuple Chat</a></div>";
}

function print_val_buttons($word) {
    echo "<form method='post' action='dictionary.php'>";
    echo "<div id='val'><button name='val' value='{$word}-1'/>This Word Is Positive (+)</button>";
    echo "<button name='val' value='{$word}-2'>This Word Is Negative (-)</button>";
    echo "<button name='val' value='{$word}-0'>This Word Is Neutral</button></div>";
    echo "</form>";
}