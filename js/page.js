// Aliases
function cl(s) { console.log(s); }
function ap(s) { $( 'body' ).append(s); }

var lt = "";

function ot(t, p) {
    s = "<" + t;
    if(typeof(p) !== undefined) {
        $.each( p, function(k, v) { s += ( " " + k + '="' + v + '"'); } );
    }
    s += ">";
    lt = t;
    return s;
}

function ct(t) {
    if(typeof(t) === undefined) {
        t = lt;
    }
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
    s = br() + ot("div") 
        + "Cash in Account: " + data.cash + br()
        + "Current trading fee: " + data.fee + br()
        + "All Stocks Held: " + data.stocks + br()
        + "Current Value:" + br()
        + "Last Change: " +data.timestamp
    + ct();
    ap(s);
}

function print_stock_info(data) {
    ot('div',{ id: 'stock-info' } );
    s = "Search run on " + data.query.created + br()
    + "Data for ticker symbol: " + data.query.results.quote.symbol + br()
    + "Average Daily Volume: " + data.query.results.quote.AverageDailyVolume + br()
    + "Change: " + data.query.results.quote.Change + br()
    + "Days Low: " + data.query.results.quote.DaysLow + br()
    + "Days High: " + data.query.results.quote.DaysHigh + br()
    + "Year Low: " + data.query.results.quote.YearLow + br()
    + "Year High: " + data.query.results.quote.YearHigh + br()
    + "Market Capitalization: " + data.query.results.quote.MarketCapitalization + br()
    + "Last Trade Price: " + data.query.results.quote.LastTradePriceOnly + br()
    + "Days Range: " + data.query.results.quote.DaysRange + br()
    + "Name: " + data.query.results.quote.Name + br()
    + "Volume: " + data.query.results.quote.Volume + br()
    + "Stock Exchange: " + data.query.results.quote.StockExchange
    ct();
    ap(s);
}

function print_trade_history(data) {
    s = ot("div") + ot("table",{id: 'trade-history', class: 'pure-table'})
    + q('th','Trade ID') + q('th','Buy/Sell') + q('th','Symbol')
    + q('th','Cost') + q('th','Currency') + q('th','Date/Time')
    + ot("h2") + "Trade History" + ct("h2");
    for (i = 0; i < data.length; i++) {
        s += ot("tr")
        for(j = 0; j < data[i].length; j++) {
            s += ot("td") + data[i][j]; ct("td");
        }
        s += ct("tr");
    }
    s += ct("table") + ct("div");
    ap(s);
}

$.post( "index.php", { test: 'true', symbol: 'GOOG', history: 'true' } , function( data ) {
    result = JSON.parse(data);
    console.log(result);
    print_trade_history(result.history.trades);
    print_current_state(result.state);
    print_stock_info(result.symbol);
});

ap( q("h1","I Trade Google") );
br();
print_search();
br();