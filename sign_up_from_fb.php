<?php
include "ajax_header.php";
$status = $_POST['status'];
$idCliente = $_POST['idCliente'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$db->select("select idCliente, usuario from cliente where usuario = '$email'", array("idc", "usuario"));
$json = '';
if ($status === 'connected') {
	if (empty($usuario)) {
		mysqli_query($con, "update cliente set nome = '$nome', usuario = '$email' where idCliente = $idCliente");
		$json = '{ "status" : "Signed up" }';
		$_SESSION['user'] = $idCliente;
	} elseif ($idCliente != $idc) {
		$_SESSION['user'] = $idc;
		$json = '{ "status" : "Logged in" }';
	}
) else {
	$_SESSION['user'] = NULL;
}
echo $json;
?>
