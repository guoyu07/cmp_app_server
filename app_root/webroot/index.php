<?php
//KO ?echo file_get_contents("http://baidu.com/");
//require_once 'index_example.php';

print '<pre>';
print_r(dns_get_record("baidu.com"));

/*
https://forum.nginx.org/read.php?3,212362,212372
In the chroot, create 

/dev/urandom 
/etc/resolv.conf 
/lib64/libnss_dns_2.5.so 
 */
