# payeer

Usage.
Exmaple:

$p = new Payeer([
    'key' => 'key from payeer.com',
    'id' => 'id from payeer.com'
]);

$r = $p->info();

Return outpu in json format:

{"success":true,"limits":{"requests":[{"interval":"min","interval_num":1,"limit":600}],"weights":[{"interval":"min","interval_num":1,"limit":1200}],"orders":[{"interval":"min","interval_num":1,"limit":120},{"interval":"day","interval_num":1,"limit":100000}]},"pairs":{"BTC_USD":{"price_prec":2,"min_price":"3062.79","max_price":"58193.03","min_amount":0.0001,"min_value":0.5,"fee_maker_percent":0.01,"fee_taker_percent":0.095},"BTC_RUB":...

Documentation https://github.com/payeer/docs/blob/main/trade-api/ru.md