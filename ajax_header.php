<?php
session_save_path("/tmp");
session_start();
header('Content-Type: application/json');
require_once "funcoes/funcoesAdministrativas.php";

$db = new DBFunctions();

$con = $db->Connect('localhost', 'webenque_enquetes', 'root', '5hondee5WBa0');
//$con = $db->Connect('localhost', 'webenque_enquetes', 'root', ''); 
mysqli_query($con, "SET NAMES 'utf8'");
mysqli_query($con, 'SET character_set_connection=utf8');
mysqli_query($con, 'SET character_set_client=utf8');
mysqli_query($con, 'SET character_set_results=utf8');
$idu = ($_SESSION['user'] !== NULL) ? $_SESSION['user'] : $_POST['cd_usuario'];
$dateformat = "d/m/Y";
$timeformat = "H:i:s";
?>
