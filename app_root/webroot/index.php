<?php

error_reporting(E_ERROR|E_COMPILE_ERROR|E_PARSE|E_CORE_ERROR|E_USER_ERROR);
require_once 'EnvHelper.php';

$MY_HOSTNAME=EnvHelper::getMyHostName();
$MY_URI=EnvHelper::getMyUri();
$MY_SCHEME=EnvHelper::getMyScheme();

function _tmpCheckAuth(){
	$strAuthUser= $_SERVER['PHP_AUTH_USER'];
	$strAuthPass= $_SERVER['PHP_AUTH_PW'];

	if (! ($strAuthUser == "1" &&  $strAuthPass == "1")){//tmp
		header('WWW-Authenticate: Basic realm="AUTH"');
		header('HTTP/1.0 401 Unauthorized');
		throw new Exception("Pwd Wrong");
	}
}

//require '
echo json_encode(array(
'STS'=>'KO',
'errmsg'=>'WRONG ENTRY',
'SvrTime'=>date('YmdHis'),
'MyHost'=>$MY_SCHEME,
'MyURI'=>$MY_URI,
'MyScheme'=>$MY_SCHEME,
//'MyUri'=>getMyUri(),
));


