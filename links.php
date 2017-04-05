<?php
require_once('include/top.php');
?>

<div id="search">
    <form method="get">
        <input id="search_form" type="search" name="url" placeholder=" <?= ( isset( $_GET['url'] ) ? "Address: " .  $_GET['url'] : 'Enter A URL' ) ?> "/>
    </form>
</div>

<?php
if(isset($_GET['url'])) {
    //$url = ['g' => 'http://www.google.com', 't' => 'http://www.investors.com/category/market-trend/stock-market-today/'];
    $html = url_get_contents($_GET['url']);
    $dom = new Query($html);
    $link_results = $dom->execute('a');

    print_links($link_results);
}
?>
