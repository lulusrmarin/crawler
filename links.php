<?php
require_once("functions.php");
check_search_params($_GET);

require_once('include/top.php');
print_search_form('links');
use Zend\Dom\Query;
//var_dump($_GET);

if( isset($_GET['s']) ) {
    //$url = ['g' => 'http://www.google.com', 't' => 'http://www.investors.com/category/market-trend/stock-market-today/'];
    $html = url_get_contents($_GET['s']);
    $dom = new Query($html);
    $link_results = $dom->execute('a');
    print_links($link_results);
}
?>
