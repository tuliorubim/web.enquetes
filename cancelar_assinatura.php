<?php
session_start();
include "bd.php";
$idSession = 'user';
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php include "head.php"; ?>
<body>
<?php include "header.php"; ?> 

<!-- MENU DO USUÁRIO -->
<div class="container">
    <div class="bkg-menu-cliente">
	<div class="row">
    <div class="col-md-8">
    <?php include 'menu_cliente.php'; ?>
    </div>
    </div>
    </div>
    <div class="row">
    <div class="col-md-6">
	<h2>CANCELAMENTO DE ASSINATURA</h2>
    <?php
	select("select nome, usuario from cliente where idCliente = $idu", array('nome', 'email'));
	if (strlen($email) > 0) {
		$POST = array();
		$POST['email'] = $email;
		$POST['name'] = $nome;
		if (empty($nome)) $POST['name'] = $email;
		$POST['subject'] = "Cancelamento de assinatura";
		$POST['message'] = "O usu&aacute;rio $email, cujo id &eacute; $idu, est&aacute; solicitando cancelamento de sua assinatura.";
		sendEmail ($POST, array("tfrubim@gmail.com"), array('webenquetes@webenquetes.com.br', '%@,_#?{.66$('));
		$status = (strpos($status, "success") !== FALSE) ? "Seu pedido de cancelamento de assinatura foi enviado corretamente." : "Ocorreu um erro no envio do seu pedido de cancelamento de assinatura. Tente novamente.";
		$color = (strpos($status, "correta") !== FALSE) ? "green" : "";
		write_status($color);
	}
	?>
    </div>
    </div>
</div>

<div class="bkg-enquetes-h">
<?php include "latest_polls.php"; ?>
</div>

<?php include "footer.php"; ?>

</html>
