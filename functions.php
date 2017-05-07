<?php
require_once('functions/print.php');

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
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/53.0");
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

function log_word($conn, $r) {
    $conn->query("INSERT INTO log SET log_id = '', url = '{$r[0]}', word='{$r[1]}', frequency='{$r[2]}', `timestamp` = NOW() ");
    if($conn->error) {
        echo $conn->error;
    }
}

function change_val($conn, $word, $i) {
    $conn->query("UPDATE entries SET val = {$i} WHERE word = '{$word}'");
    if($conn->error) {
        echo $conn->error;
    }
    else { return true; }
}

//////////// HTML Functions
function open_table($r) {
    $s = "<table class='pure-table'><tr>";
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

//Todo:  Merge these functions
function add_definition($conn, $r) {
    $conn->query("INSERT INTO entries SET word = '{$r[0]}', wordtype='{$r[1]}', definition='{$r[2]}'");
    if(!$conn->error) {
        echo "<h2>That Definition Has Been Added!</h2>";
        return true;
    }
    else { echo $conn->error; }
}

function update_definition($conn, $r) {
    $conn->query("UPDATE entries SET word = '{$r[0]}', wordtype='{$r[1]}', definition='{$r[2]}' WHERE id = {$r[3]}");
    if(!$conn->error) {
        echo "<h2>That Definition Has Been Updated!</h2>";
        return true;
    }
    else { echo $conn->error; }
}

function delete_definition($conn, $id) {
    $conn->query("DELETE FROM entries WHERE id = {$id}");
    if(!$conn->error) {
        echo "<h2>That Definition Has Been Deleted!</h2>";
        return true;
    }
    else { echo $conn->error; }
}

function lookup_word($conn, $s) {
    $s = mysql_escape_string($s);
    $stmt = $conn->prepare("SELECT * FROM entries WHERE word = ?");
    $stmt->bind_param('s', $s );
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all();
    return $rows;
}

//TODO:  Parameter Bind / Prepare
function lookup_all_words($conn) {
    $stmt = $conn->prepare("SELECT word FROM entries");
    $stmt->execute();
    $result = $stmt->get_result();
    $words = $result->fetch_all();
    return $words;
}

function get_text_from_url($url) {
    $html = url_get_contents($url);
    $text = strip_tags($html);
    //vd($text);
    $words = explode(" ",$text);
    $merge = merge_array($words);
    arsort($merge);

    return $merge;
}

//Basic web scraping function
function scrape_between($data, $start, $end){
    $data = stristr($data, $start); // Stripping all data from before $start
    $data = substr($data, strlen($start));  // Stripping $start
    $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
    $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
    return $data;   // Returning the scraped data from the function
}

?>