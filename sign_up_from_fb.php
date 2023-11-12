<?php
include "ajax_header.php";
$idCliente = $_POST['idCliente'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$db->select("select idCliente, usuario from cliente where usuario = '$email'", array("idc", "usuario"));
$json = '';
if (empty($usuario)) {
	$db->select("select usuario from cliente where idCliente = $idCliente", array("email2"));
	if (empty($email2)) {
		mysqli_query($con, "update cliente set nome = '$nome', usuario = '$email' where idCliente = $idCliente");
		$_SESSION['user'] = $idCliente;
	} else {
		mysqli_query($con, "insert into cliente (nome, usuario) values ('$nome', '$email')");
		$db->select("select idCliente from cliente where usuario = '$email'", array("idCliente2"));
		$_SESSION['user'] = $idCliente2;
	}
	$json = '{ "status" : "justConnected" }';
} elseif ($idCliente != $idc) {
	$_SESSION['user'] = $idc;
	$json = '{ "status" : "justConnected", "ses" : "'.$_SESSION['user'].'" }';
}
echo $json;
?>
