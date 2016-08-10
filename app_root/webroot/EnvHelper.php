<?php

class EnvHelper
{
	public static function getMyHostName(){

#$HTTP_VIA=strtolower($_SERVER['HTTP_VIA']);
#$HTTP_X_FORWARDED_SERVER=strtolower($_SERVER['HTTP_X_FORWARDED_SERVER']);
		$SERVER_NAME=strtolower($_SERVER['SERVER_NAME']);
		$HTTP_HOST=strtolower($_SERVER['HTTP_HOST']);
		$MY_HOSTNAME=$HTTP_HOST or $SERVER_NAME;
		return strtolower($MY_HOSTNAME);
	}
	public static function getMyUri(){
		//$PHP_SELF=$_SERVER['PHP_SELF'];
		$MY_URI=$_SERVER['REQUEST_URI'];
		#NO !!! if(!$MY_URI) $MY_URI=$_SERVER['PATH_INFO'];
		return $MY_URI;
	}
	public static function getMyScheme(){
		$MY_SCHEME=$_SERVER['HTTP_X_FORWARDED_PROTO'];
		if(!$MY_SCHEME) $MY_SCHEME=$_SERVER['REQUEST_SCHEME'];
		return strtolower($MY_SCHEME);
	}
	public static function getMyIsoDateTime(){
		return date('YmdHis');
	}
}
