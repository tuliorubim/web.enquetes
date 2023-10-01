<?php
include "ajax_header.php";
$nome = $_POST['nome'];
$email = $_POST['email'];
$db->select("select usuario from cliente where email = '$email'", array("usuario"));
if (empty($usuario)) {
	mysqli_query($con, "insert into cliente (nome, usuario) values ($nome, $email)");
	echo '{ "status" : "Sucesso" }';
} else echo '{ "status" : "Already signed up" }';
?>
