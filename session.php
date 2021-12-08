<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
/*require_once "funcoes/funcoesBD.php";
$con = Connect('localhost', 'webenque_enquetes', 'webenque_tes', 'L.uL&DTb?eTr');
//$con = Connect('localhost', 'webenque_enquetes', 'root', ''); 
mysqli_query($con, "SET NAMES 'utf8'");
mysqli_query($con, 'SET character_set_connection=utf8');
mysqli_query($con, 'SET character_set_client=utf8');
mysqli_query($con, 'SET character_set_results=utf8');*/
$idSession = 'user';
$idu = ($_COOKIE['user'] !== NULL) ? $_COOKIE['user'] : "666";
echo 'gs({ "session" : "'.$idu.'..." })';
?>
