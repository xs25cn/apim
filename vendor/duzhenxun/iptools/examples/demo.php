<?php
require '../vendor/autoload.php';

$ip = '203.83.56.56';
$obj = new IpTools\IpArea();
echo $obj->get($ip);
