<?php
include "ajax_header.php";
$json = '';
$idu = $_POST['cdu'];
$email = $_POST['email'];
$admin = new AdminFunctions();
$senha = $admin->codeGenerator();
$data = date("$dateformat $timeformat");
$db->save('cliente', array('idCliente', 'usuario', 'senha', 'data_cadastro', 'permitir_email'), array('integer', 'varchar', 'varchar', 'datetime', 'boolean'), array($idu, $email, $senha, $data, 1));
include "sendmail.php";
send_email ("tfrubim@gmail.com", array($email), "Sua senha Web Enquetes", $db->html_encode("Sua senha para acesso à área restrita deste site é $senha"));
if (!mysqli_error($con) && strpos($status, "corretamente")) {
	$json = '{ "status" : "sucesso" }';
}
echo $json;
?>
