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
		$PHP_SELF=$_SERVER['PHP_SELF'];

		$MY_URI=$PHP_SELF;
		return $MY_URI;
	}
	public static function getMyScheme(){
		$MY_SCHEME=$_SERVER['HTTP_X_FORWARDED_PROTO'];
		return strtolower($MY_SCHEME);
	}
	public static function getMyIsoDateTime(){
		return date('YmdHis');
	}
}
