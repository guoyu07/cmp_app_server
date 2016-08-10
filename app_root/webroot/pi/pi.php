<?
error_reporting(E_ERROR|E_COMPILE_ERROR|E_PARSE|E_CORE_ERROR|E_USER_ERROR);
//calculate correct host name (which expected same at the one in the browser...?)
//NOTES: getHostName() is useless!!
function _tmpCheckAuth(){
	$strAuthUser= $_SERVER['PHP_AUTH_USER'];
	$strAuthPass= $_SERVER['PHP_AUTH_PW'];

	if (! ($strAuthUser == "1" &&  $strAuthPass == "1")){//tmp
		header('WWW-Authenticate: Basic realm="AUTH"');
		header('HTTP/1.0 401 Unauthorized');
		throw new Exception("Pwd Wrong");
	}
}
_tmpCheckAuth();

echo getcwd().'<br/>';
phpinfo();
die;
