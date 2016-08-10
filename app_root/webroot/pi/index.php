<?
error_reporting(E_ERROR|E_COMPILE_ERROR|E_PARSE|E_CORE_ERROR|E_USER_ERROR);
//calculate correct host name (which expected same at the one in the browser...?)
//NOTES: getHostName() is useless!!
function getMyHostName(){

#$HTTP_VIA=strtolower($_SERVER['HTTP_VIA']);
#$HTTP_X_FORWARDED_SERVER=strtolower($_SERVER['HTTP_X_FORWARDED_SERVER']);
	$SERVER_NAME=strtolower($_SERVER['SERVER_NAME']);
	$HTTP_HOST=strtolower($_SERVER['HTTP_HOST']);
	$MY_HOSTNAME=$HTTP_HOST or $SERVER_NAME;
	return strtolower($MY_HOSTNAME);
}
function getMyUri(){
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$MY_URI=$PHP_SELF;
	return $MY_URI;
}
function getMyScheme(){
	$MY_SCHEME=$_SERVER['HTTP_X_FORWARDED_PROTO'];
	return strtolower($MY_SCHEME);
}

function _tmpCheckAuth(){
	$strAuthUser= $_SERVER['PHP_AUTH_USER'];
	$strAuthPass= $_SERVER['PHP_AUTH_PW'];

	if (! ($strAuthUser == "1" &&  $strAuthPass == "1")){//tmp
		header('WWW-Authenticate: Basic realm="AUTH"');
		header('HTTP/1.0 401 Unauthorized');
		throw new Exception("Pwd Wrong");
	}
}
#_tmpCheckAuth();

//echo getcwd().'<br/>';
#phpinfo();
?>
<a href='javascript:location.reload();'>Refresh</a>
<?
echo '<pre>';
$MY_HOSTNAME=getMyHostName();
$MY_URI=getMyUri();
$MY_SCHEME=getMyScheme();
print "$MY_URI\n";
print "$MY_HOSTNAME\n";
print "$MY_SCHEME\n";
print '<hr/>';
?>
<script>
document.writeln(screen.width + 'x' + screen.height);
</script>
<?php
#var_dump($_SERVER);
#phpinfo();
