<?php
header('Content-Type: application/json');
$exp = $_GET['expression'];
eval("\$res = $exp;");
$json = '{ "res" : "'.$res.'" }';
echo $_GET['callback']."(".$json.")"; 
?>
