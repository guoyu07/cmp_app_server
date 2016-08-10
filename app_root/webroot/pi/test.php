<?php
require 'inc.test.php';
?>
<a href='javascript:location.reload();'>Refresh</a>
<?
echo '<pre>';
$MY_HOSTNAME=EnvHelper::getMyHostName();
$MY_URI=EnvHelper::getMyUri();
$MY_SCHEME=EnvHelper::getMyScheme();
print "MY_URI=$MY_URI\n";
print "MY_HOSTNAME=$MY_HOSTNAME\n";
print "MY_SCHEME=$MY_SCHEME\n";
print "MY_ISODATETIME=".EnvHelper::getMyIsoDateTime()."\n";
print '<hr/>';
var_dump($_SERVER);
print '<hr/>';
?>
<script>
document.writeln(screen.width + 'x' + screen.height);
</script>
<?php
#var_dump($_SERVER);
#phpinfo();
