<?php
//Use a curl (Especially useful if get_file_contents disabled on web host)
//to pull all data from a URL
function url_get_contents ($url)
{
    if (!function_exists('curl_init')) {
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

    return $output;
}

function br($i = 1) {
    for($j = 0; $j < $i; $j++) {
        echo "<br/>";
    }
}

$url = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.quote%20where%20symbol%20%3D%20%22goog%22&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
$result = url_get_contents($url);
//var_dump($result);
$data = json_decode( $result );

echo "Search run on " . $data->query->created;
br();
echo "Data for ticker symbol: " . $data->query->results->quote->symbol;
br();
echo "Average Daily Volume: " . $data->query->results->quote->AverageDailyVolume;
br();
echo "Change: " . $data->query->results->quote->Change;
br();
echo "Days Low: " . $data->query->results->quote->DaysLow;
br();
echo "Days High: " . $data->query->results->quote->DaysHigh;
br();
echo "Year Low: " . $data->query->results->quote->YearLow;
br();
echo "Year High: " . $data->query->results->quote->YearHigh;
br();
echo "Market Capitalization: " . $data->query->results->quote->MarketCapitalization;
br();
echo "Last Trade Price: " . $data->query->results->quote->LastTradePriceOnly;
br();
echo "Days Range: " . $data->query->results->quote->DaysRange;
br();
echo "Name: " . $data->query->results->quote->Name;
br();
echo "Volume: " . $data->query->results->quote->Volume;
br();
echo "Stock Exchange: " . $data->query->results->quote->StockExchange;

