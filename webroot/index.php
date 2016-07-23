<?php
/* vim: set tabstop=2 shiftwidth=2 softtabstop=2: */
error_reporting(E_ERROR|E_COMPILE_ERROR|E_PARSE|E_CORE_ERROR|E_USER_ERROR);
$PATH_INFO=$_SERVER['PATH_INFO'];

if($PATH_INFO){
	$proxy_url=preg_replace("/^[\/]?738\//","http__/120.55.73.8/",$PATH_INFO);
}else{
	$REQUEST_URI=$_SERVER['REQUEST_URI'];
	$proxy_url=preg_replace("/^[\/]?738\//","http__/120.55.73.8/",$REQUEST_URI);
}
//$proxy_url=str_replace("738.php/","",$proxy_url);//临时的，原意是把前面的/738给去掉.

$proxy_url =  ltrim($proxy_url,'/');
if($proxy_url){
	$proxy_url=str_replace("http__/","http://",$proxy_url);//WJC SICK HACK
	$proxy_url=str_replace("https__/","https://",$proxy_url);//WJC SICK HACK

	$_http_host_a=parse_url($proxy_url);
	$final_scheme=$_http_host_a['scheme'];
	$final_host=($_http_host_a['host'])?$_http_host_a["host"]:$host;
	$final_port=$_http_host_a["port"];
	$final_path=$_http_host_a["path"];

	if($final_scheme && $final_host){
		$mirror=$final_host;
		if($final_port==""){
			if($final_scheme=='http'){
				$final_port=80;
			}elseif($final_scheme=='https'){
				$final_port=443;
			}
		}
		//switch($final_host){
		//}
		if(in_array($final_host,array(
			//"yyljson.sinaapp.com",
			//"www.yoyoluck.com",
			//"yoyoluck.com",
			//"youhainan.sinaapp.com",
			//"www.piju.com.cn",
			//"pijucomcn.sinaapp.com",
			//"eiaibi.sinaapp.com",
			"120.55.73.8",
			//"120.55.73.8:80",
			//"120.55.73.8:81",
			"acedemo.sinaapp.com",
		))){
		}else{
			print '{"errmsg":"not allow pxu '.$final_host.'"}';die;
		}
	}else{
		print "TODO $proxy_url<br/>";
		print "REQUEST_URI=$REQUEST_URI<br/>";
		print "PATH_INFO=$PATH_INFO<br/>";
		die;
	}
} else {
	print "Request Error";
	die;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
$QUERY_STRING=$_SERVER['QUERY_STRING'];
if ($QUERY_STRING!=='') {
	$final_path .= "?$QUERY_STRING";
}

require("px.php");
$px=new px;
//echo "$px->forward($final_path, $final_host, $final_port, $final_scheme);";
$px->forward($final_path, $final_host, $final_port, $final_scheme);

flush();
die;

