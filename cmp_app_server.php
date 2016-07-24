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
				{
					$headers[$h[0]] = trim($h[1]);
				}
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
if (!function_exists('http_build_cookie'))
{
	//@ref http://php.net/manual/fa/function.http-parse-cookie.php
	function http_build_cookie( $data )
	{
		if( is_array( $data ) )
		{
			$cookie = '';
			foreach( $data as $k=>$v )
			{
				$cookie[] = urlencode($k).'='.urlencode($v);
			}
			if( count( $cookie ) > 0 )
			{
				return trim( implode( '; ', $cookie ) );
			}
		}
		return false;
	}
}

require_once 'PhpfpmClient.php';

$http = new swoole_http_server("0.0.0.0", 9501);

//TODO override by console parameters...
define("FPM_HOST",'localhost');
define("FPM_PORT",'9000');

#define("FPM_HOST",'unix:///path/to/php/socket');
#define("FPM_PORT",-1);

function get_php_fpm_client(){
	//TODO get from the php-fpm-client-pool to get the vacant one.
	$client = new PhpfpmClient(FPM_HOST, FPM_PORT);
	return $client;
}

$http->on('request', function ($request, $response) {
	$phpfpmclient = get_php_fpm_client();

	try{
		//_SERVER
		$p=array_change_key_case($request->server,CASE_UPPER);

		//REQUEST_URI
		$REQUEST_URI=$p['REQUEST_URI'];

		//REQUEST HEADERS
		$req_header_a=array();
		foreach($request->header as $k=>$v){
			$kk = 'HTTP_'.strtoupper(str_replace('-','_',$k));
			$req_header_a[$kk]=$v;
		}
		$p=array_merge($p, $req_header_a);

		//_COOKIE
		if(isset($request->cookie)){
			$p['HTTP_COOKIE']=http_build_cookie($request->cookie);
		}

		//TODO change SCRIPT_FILENAME for different REQUEST_URI...
		//SCRIPT_FILENAME for fpm:
		$SCRIPT_FILENAME='index.php';
		$p['SCRIPT_FILENAME']=$SCRIPT_FILENAME;

		$REQUEST_METHOD=$p['REQUEST_METHOD'];

		print "$REQUEST_METHOD $REQUEST_URI\n";

		if($REQUEST_METHOD=='POST'){
			//TODO FILES handling...debug..
			##print "$REQUEST_METHOD $REQUEST_URI\n";
			$post_s=$request->rawContent();
			//$p['CONTENT_TYPE']='application/x-www-form-urlencoded';//to observe
			$p['CONTENT_LENGTH']=strlen($post_s);//IMPORTANT...
			$s=$phpfpmclient->request( $p, $post_s );

		}elseif($REQUEST_METHOD=='GET'){
			$s=$phpfpmclient->request( $p, "" );

		}else{
			//Other than GET/POST is not yet supported
			$s="TODO $REQUEST_METHOD $REQUEST_URI";
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
			if($k=='Status'){
				$resp_status=preg_replace("/^(\\d*)(.*)$/","\\1",$v);
				$response->status($resp_status);
				continue;
			}
			if(is_string($v))
			{
				#print "NormalHeader [$k:$v]\n";
				$response->header($k,$v);
			}
			elseif(is_array($v))
			{
				//use the last one...
				$v=array_pop($v);
				#print "AdjustHeader [$k:$v]\n";
				$response->header($k,$v);
			}
			else
			{
				print($k);print_r($v);print " !!!!!!!!!! \n";
			}
		}
	}else{
		//special case of having no header...
		$resp_body = $s;
	}
	if($resp_body){
		$response->write($resp_body);
	}
	$response->end();
});

#$http->on('pipeMessage', function($serv, $src_worker_id, $data) {
#	echo "#{$serv->worker_id} message from #$src_worker_id: $data\n";
#});

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
