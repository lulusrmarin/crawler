<?php
function ot($tag, $params = []) {
    echo "<{$tag}";
    foreach($params as $k => $v) {
        echo " {$k}=\"{$v}\"";
    }
    echo ">";
}

function ct($tag) {
    echo "</{$tag}>";
}

function q($tag, $text, $params = NULL) {
    ot($tag, $params);
    echo $text;
    ct($tag);
}

function print_stock_info($data) {
    ot('div',['id' => 'stock-info']);
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
    ct(div);
}

function print_current_state($row) {
    ot('div',['id' => 'state']);
    echo "Cash in Account: " , $row['cash'];
    br();
    echo "Current trading fee: " . $row['fee'];
    br();
    echo "All Stocks Held: " . $row['stocks'];
    br();
    echo "Current Value:" ;
    br();
    echo "Last Change: " . $row['timestamp'];
    br();
    ct('div');
}

function print_search() {
    ot("form");
    q("label","Search: ");
    ot("input", [ 'type' => 'text', 'name' => 's' ] );
    ct("form");
}