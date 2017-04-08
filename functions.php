<?php
//Use a curl (Especially useful if get_file_contents disabled on web host)
//to pull all data from a URL
function url_get_contents ($url) {
if (!function_exists('curl_init')){
die('CURL is not installed!'); //Curl will return a false bool and die here if CURL is not enabled.
}
$ch = curl_init(); //Return curl handle
curl_setopt($ch, CURLOPT_URL, $url); //The URL to fetch
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Return curl contents as opposed to dumping
curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate,sdch'); //set encoding
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); //follow redirects
curl_setopt($ch, CURLOPT_VERBOSE, 0);  //I don't know some shit
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/21.0");
$output = curl_exec($ch);


if (FALSE === $output)
throw new Exception(curl_error($ch), curl_errno($ch));

curl_close($ch); //Close handle
return $output; //Return contents
}

function merge_array($r) {
    $merge = array();
    foreach( $r as $word ) {
        //echo $word . "<br/>";
        $merge[$word]++;
    }
    return $merge;
}

function show_dom_node(DOMNode $domNode) {
    if(!isset($tag_array)) { $tag_array = array(); }
    foreach ($domNode->childNodes as $node)
    {
        echo "<div>" . htmlspecialchars($node->nodeName.':'.$node->nodeValue) . "</div>";
        $tag_array[$node->nodeName]++;
        if($node->hasChildNodes()) {
            show_dom_node($node);
        }
    }

    if($node === end( $domNode->childNodes ) ) { echo "hello"; }
}

function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}

function set_search_values($s) {
    if($s == 'dict') {
        $r['placeholder'] = "Enter A Word";
        $r['action'] = "dictionary.php";
    }
    elseif($s == 'words' ) {
        $r['placeholder'] = "Enter A Link";
        $r['action'] = "words.php";
    }
    elseif($s == 'links') {
        $r['placeholder'] = "Enter A Link";
        $r['action'] = "links.php";
    }
    else {
        $r['placeholder'] = "Enter A Search";
        $r['action'] = "index.php";
    }

    return $r;
}

function check_search_params($r = NULL) {
    if( !isset($r) ) { return false; }
    if( $r['st'] === "0" ) { header('Location: dictionary.php?s=' . $r['s']); }
    if( $r['st'] === "1" ) { header('Location: words.php?s=' . $r['s']); }
    if( $r['st'] === "2" ) { header('Location: links.php?s=' . $r['s']); }
}

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

function print_word_table($name,$cols,$r,$button = false) {
    $max = reset($r);

    echo "<div class='word-table'>" . "<h3 class='table_title'>" . $name . "</h3>";
    echo open_table($cols);
    foreach($r as $k => $v) {
        echo print_word_rows($k, $v, $max,$button);
    }
    echo close_table();
    echo "</div>";
}

function print_correction_button($s) {
    $s = "<form action='dictionary.php'><button>Is this incorrect?</button></form>";
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
        <input type=\"radio\" name=\"st\" value=\"0\" " . ( $page == 'dict' ? "checked='checked'" : "" ) . " />Dictionary
        <input type=\"radio\" name=\"st\" value=\"1\" " . ( $page == 'words' ? "checked='checked'" : "" ) . " />Words in Page
        <input type=\"radio\" name=\"st\" value=\"2\" " . ( $page == 'links' ? "checked='checked'" : "" ) . " />Links in Page
    </div>";
}

function print_definitions($rows) {
    print_add_buttons();
    print_hidden_textbox( $rows[0][0] );
    $i = 0;
    foreach( $rows as $row ) {
        print_definition($row, $i);
        //echo $i;
        $i++;
    }
}

function print_definition($row, $i = NULL) {
    echo $row['3'];
    echo "<div id='def'>";
    echo "<form action='dictionary.php' method='post'>";
    echo "<word id='word{$i}'>{$row[0]}</word>
    <word_type id='wt{$i}'>{$row[1]}</word_type>
    <definition id='def{$i}'>{$row[2]}</definition>";
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
                        <option value='N.'>N.</option>
                        <option value='V.'>V.</option>
                        <option value='Adj.'>Adj.</option>
                        <option value=''>???</option>
                    </select>
                    <textarea name='d'>Test</textarea>
                    <button name='a' value='{$s}'>Enter your definition!</button>
                </form>    
            </div>";
}

function print_not_found($s) {
    echo "<div id='def'><h2>{$s}</h2> is not in our dictionary.  If this is a valid word, please help us by adding it.</div>";
    print_add_buttons();
    print_hidden_textbox($s);
}

///////// DB Functions
function db($dbr) {
    $conn = new mysqli($dbr[0], $dbr[1], $dbr[2], $dbr[3]);
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    return $conn;
}

function add_definition($conn,$r) {
    $conn->query("INSERT INTO entries SET word = '{$r[0]}', wordtype='{$r[1]}', definition='{$r[2]}'");
    if(!$conn->error) {
        return true;
    }
    else { echo $conn->error; }
}

function lookup_word($conn, $s) {
    $stmt = $conn->prepare("SELECT * FROM entries WHERE word = ?");
    $stmt->bind_param('s', $s );
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all();
    return $rows;
}

function lookup_all_words($conn) {
    $stmt = $conn->prepare("SELECT word FROM entries");
    $stmt->execute();
    $result = $stmt->get_result();
    $words = $result->fetch_all();
    return $words;
}

//////////// HTML Functions
function open_table($r) {
    $s = "<table><tr>";
    foreach($r as $item) {
        $s .= "<th>" . $item . "</th>";
    }
    $s .= "</tr>";
    return $s;
}

function close_table() {
    return "</table>";
}

function br($i = 1) {
    for($j = 0; $j < $i; $j++) {
        echo "<br/>";
    }
}

function href($text,$url,$onclick = NULL, $id = NULL) {
    echo "<a href='" . $url . "'" . ( isset($onclick) ? " onClick=" . $onclick : "" ) .
        ( isset($id) ? " id='" . $id . "'" : "" ) . ">" . $text . "</a>";
}

function button($text,$id) {
    echo "<button id='{$id}'>{$text}</button>";
}

function include_jquery() {
    echo'<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>';
}

//Dictionary
function sort_words($conn, $r) {
    foreach( $r as $k => $v ) {
        $row = lookup_word($conn, $k);
        if($row) {
            $r['words'][$k] = $v;
        }
        else {
            $r['non_words'][$k] = $v;
        }
    }
    return $r;
}
?>