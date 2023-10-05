<?php
include "ajax_header.php";
$idCliente = $_POST['idCliente'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$db->select("select idCliente, usuario from cliente where email = '$email'", array("idc", "usuario"));
$json = '';
if (empty($usuario)) {
	mysqli_query($con, "update cliente set nome = '$nome', usuario = '$email' where idCliente = $idCliente");
	$json = '{ "status" : "Signed up" }';
} elseif ($idCliente != $idc) {
	$_SESSION['user'] = $idc;
	$json = '{ "status" : "Logged in" }';
}
echo $json;
?>
