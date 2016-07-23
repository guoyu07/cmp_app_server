<?php
$port=9501;
$http = new swoole_http_server("0.0.0.0", $port);
$http->on('request', function ($request, $response) {
	ob_start();
	echo "$port swoole ".rand();
	$s=ob_get_clean();
	$s="<pre>$s</pre>";
	$response->end($s);
});
$http->start();
