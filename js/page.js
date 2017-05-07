// Aliases
function cl(s) { console.log(s); }
function ap(s) { $( 'body' ).append(s); }

function ot(t, p) {
    s = "<" + t;
    if(typeof(p) !== undefined) {
        $.each( p, function(k, v) { s += ( " " + k + '="' + v + '"'); } );
    }
    s += ">";
    return s;
}

function ct(t) {
    return ("</" + t + ">");
};

function q(t, s, p) {
    a = ot(t, p);
    a += s;
    a += ct(t);
    return a;
}

function br(i) {
    i = (typeof i !== 'undefined') ?  i : 1;
    console.log(i);
    for (j = 0; j < i; j++) {
       return "<br/>";
    }
}

function print_search() {
    s = ot("form", {method: 'post'});
    s += q("label", "Search: ", { id: 'search' });
    s += ot("input", { type: 'text', name: 's' } );
    s += ct("form");
    ap(s);
}

function print_current_state(data) {
    s = br();
    s += ot("div");
    s += "Cash in Account: " + data.cash;
    s += br();
    s += "Current trading fee: " + data.fee;
    s += br();
    s += "All Stocks Held: " + data.stocks;
    s += br();
    s += "Current Value:";
    s += br();
    s += "Last Change: " +data.timestamp;
    s += ct("div");
    ap(s);
}

function print_stock_info(data) {
    ot('div',{ id: 'stock-info' } );
    s = "Search run on " + data.query.created;
    s += br();
    s += "Data for ticker symbol: " + data.query.results.quote.symbol;
    s += br();
    s += "Average Daily Volume: " + data.query.results.quote.AverageDailyVolume;
    s += br();
    s += "Change: " + data.query.results.quote.Change;
    s += br();
    s += "Days Low: " + data.query.results.quote.DaysLow;
    s += br();
    s += "Days High: " + data.query.results.quote.DaysHigh;
    s += br();
    s += "Year Low: " + data.query.results.quote.YearLow;
    s += br();
    s += "Year High: " + data.query.results.quote.YearHigh;
    s += br();
    s += "Market Capitalization: " + data.query.results.quote.MarketCapitalization;
    s += br();
    s += "Last Trade Price: " + data.query.results.quote.LastTradePriceOnly;
    s += br();
    s += "Days Range: " + data.query.results.quote.DaysRange;
    s += br();
    s += "Name: " + data.query.results.quote.Name;
    s += br();
    s += "Volume: " + data.query.results.quote.Volume;
    s += br();
    s += "Stock Exchange: " + data.query.results.quote.StockExchange;
    ct("div");
    ap(s);
}

$.post( "index.php", { test: 'true', symbol: 'GOOG' } , function( data ) {
    result = JSON.parse(data);
    console.log(result);
    print_current_state(result.state);
    print_stock_info(result.symbol);
});

ap( q("h1","I Trade Google") );
br();
print_search();
br();