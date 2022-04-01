<?php
include "bd.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php include "head.php"; ?> 
<body>
<?php include "header.php"; ?> 
<div class="container">
	<div class="row">
    <!-- COLUNA ESQUERA -->
    <div class="col-md-7">
    <?php
	//include "sendmail.php";
	if ($_POST['send'] === "ENVIAR") {
		$_POST['message'] = $_POST['message']."\n\n".$_POST['email'];
		$we->sendEmail ($_POST, array("tfrubim@gmail.com"));
		//$status = "Ocorreu um erro no envio da sua mensagem. Voc&ecirc; tamb&eacute; pode entrar em contato conosco por meio da nossa <a href='https://www.facebook.com/WebEnquetesEPesquisas/'>p&aacute;gina</a> do Facebook.";
		$class = (strpos($status, "correta") !== FALSE) ? "status" : "";
		$we->write_status($class);
	}
	?>    
    <!-- AQUI É O LIMITE PARA A COLUNA ESQUERDA, O CONTEÚDO DEVE ESTAR O ÍNICIO E AQUI O FIM -->
    </div>
    
	<?php include "sidebar.php"; ?>    
    
    </div>
</div>
<?php include "categorias.php"; ?>

<!-- bkg-footer -->
<div class="clearfix">
<?php include "footer.php"; ?>
</div>

</body>
</html>