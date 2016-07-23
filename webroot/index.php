<?php
$php_input = file_get_contents('php://input');
print "\nphp_input:";
var_dump($php_input);

//http://php.net/manual/zh/reserved.variables.httprawpostdata.php
//Warning
//This feature has been DEPRECATED as of PHP 5.6.0. Relying on this feature is highly discouraged.
$HTTP_RAW_POST_DATA=$GLOBALS['HTTP_RAW_POST_DATA'];
print "\n HTTP_RAW_POST_DATA:";
var_dump($HTTP_RAW_POST_DATA);

#$php_input = file_get_contents('php://input');
#if($php_input){
#	if(!$GLOBALS['HTTP_RAW_POST_DATA'])
#		$GLOBALS['HTTP_RAW_POST_DATA']=$php_input;//store for later usage if needed
#}else{
#	if($GLOBALS['HTTP_RAW_POST_DATA'])
#		$php_input=$GLOBALS['HTTP_RAW_POST_DATA'];
#}

var_dump($_POST);
#phpinfo();
