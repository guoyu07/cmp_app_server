<?php
/*
 * test file for building a cmp server,
 * which is build with swoole (as front web) and talk with php-fpm backend.
 */

if (!function_exists('http_parse_headers'))
{
	#@ref http://php.net/manual/fa/function.http-parse-headers.php
	function http_parse_headers($raw_headers)
	{
		$headers = array();
		$key = '';

		foreach(explode("\n", $raw_headers) as $i => $h)
		{
			$h = explode(':', $h, 2);

			if (isset($h[1]))
			{
				if (!isset($headers[$h[0]]))
					$headers[$h[0]] = trim($h[1]);
				elseif (is_array($headers[$h[0]]))
				{
					$headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
				}
				else
				{
					$headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
				}

				$key = $h[0];
			}
			else
			{
				if (substr($h[0], 0, 1) == "\t")
					$headers[$key] .= "\r\n\t".trim($h[0]);
				elseif (!$key)
					$headers[0] = trim($h[0]);trim($h[0]);
			}
		}
		return $headers;
	}
}
if (!function_exists('http_build_cookie')){
	//@ref http://php.net/manual/fa/function.http-parse-cookie.php
	function http_build_cookie( $data ) {
		if( is_array( $data ) ) {
			$cookie = '';
			foreach( $data as $k=>$v ) {
				$cookie[] = "$k=$v";//TODO urlencode....
			}
			if( count( $cookie ) > 0 ) {
				return trim( implode( '; ', $cookie ) );
			}
		}
		return false;
	}
}

require_once 'PhpfpmClient.php';

$http = new swoole_http_server("0.0.0.0", 9501);

#define('WEBROOT', 'webroot/');
define('WEBROOT', '');

define("FPM_HOST",'localhost');
define("FPM_PORT",'9000');

#define("FPM_HOST",'unix:///path/to/php/socket');
#define("FPM_PORT",-1);

$http->on('request', function ($request, $response) {
	$client = new PhpfpmClient(FPM_HOST, FPM_PORT);

	try{
		//_SERVER
		$p=array_change_key_case($request->server,CASE_UPPER);

		//REQUEST HEADERS
		$req_header_a=array();
		foreach($request->header as $k=>$v){
			$kk = 'HTTP_'.strtoupper(str_replace('-','_',$k));
			$req_header_a[$kk]=$v;
		}

		//REQUEST_URI
		$REQUEST_URI=$p['REQUEST_URI'];
		//TODO fwd if .php$
		if($REQUEST_URI=='/'){
			$REQUEST_URI=WEBROOT .'index.php';//TODO filter uri
		}else{
			//TODO
			print("TODO $REQUEST_URI\n");
			$response->end($REQUEST_URI);
			return;
		}
		$p=array_merge($p, $req_header_a);

		//PATH_INFO
		$p['SCRIPT_FILENAME']=$REQUEST_URI;//TODO

		//_COOKIE
		if(isset($request->cookie)){
			$p['HTTP_COOKIE']=http_build_cookie($request->cookie);
		}

		$REQUEST_METHOD=$p['REQUEST_METHOD'];
		if($REQUEST_METHOD=='POST'){
			$post_s=$request->rawContent();
			$p['CONTENT_TYPE']='application/x-www-form-urlencoded';
			$p['CONTENT_LENGTH']=strlen($post_s);
			$s=$client->request( $p, $post_s );
		}else{
			//print_r($p);
			$s=$client->request( $p, "" );
		}
	}catch(Exception $ex){
		$s=$ex->getCode() .':'.$ex->getMessage();
	}
	$eoh = strpos($s, "\r\n\r\n"); 
	if( $eoh )
	{
		$resp_header_s = substr($s, 0, $eoh);
		$resp_body = substr($s, $eoh + 4);
		$resp_header_a=http_parse_headers($resp_header_s);
		foreach($resp_header_a as $k=>$v){
			$response->header($k,$v);
		}
	}else{
		$resp_body = $s;
	}
	$response->write($resp_body);
	$response->end();
});

$http->on('pipeMessage', function($serv, $src_worker_id, $data) {
	echo "#{$serv->worker_id} message from #$src_worker_id: $data\n";
});

//TODO
//$http->addlistener("127.0.0.1", 9502, SWOOLE_SOCK_TCP);

$http->start();

//@ref
//http://php.net/manual/en/function.getallheaders.php
//http://php.net/manual/en/function.apache-request-headers.php
//if (!function_exists('getallheaders')) 
//{ 
//	function getallheaders() 
//	{ 
//		$headers = ''; 
//		foreach ($_SERVER as $name => $value) 
//		{ 
//			if (substr($name, 0, 5) == 'HTTP_') 
//			{ 
//				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
//			} 
//		} 
//		return $headers; 
//	} 
//}
//if( !function_exists('apache_request_headers') ) {
//	function apache_request_headers() {
//		$arh = array();
//		$rx_http = '/\AHTTP_/';
//		foreach($_SERVER as $key => $val) {
//			if( preg_match($rx_http, $key) ) {
//				$arh_key = preg_replace($rx_http, '', $key);
//				$rx_matches = array();
//				// do some nasty string manipulations to restore the original letter case
//				// this should work in most cases
//				$rx_matches = explode('_', strtolower($arh_key));
//				if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
//					foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
//					$arh_key = implode('-', $rx_matches);
//				}
//				$arh[$arh_key] = $val;
//			}
//		}
//		if(isset($_SERVER['CONTENT_TYPE'])) $arh['Content-Type'] = $_SERVER['CONTENT_TYPE'];
//		if(isset($_SERVER['CONTENT_LENGTH'])) $arh['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
//		return( $arh );
//	}
//}
