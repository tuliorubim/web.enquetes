<?php
include "dados_modelo.php";
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
	$cdu = $_POST['idCliente'];
	$valid = $we->valida_cliente();
	if (is_bool($valid) && ($cdu === NULL || $cdu == $we->idu)) {
		$we->formTabela6 = Dados_webenquetes::$formTabela6;
		$we->crud_cliente();
	} elseif (is_string($valid)) {
		$status = $valid;
		$we->write_status();
	}
	?>
	</div>
    
	<?php include "sidebar.php"; ?>    
    
    </div>
</div>
<?php include "categorias.php"; ?>

<!-- bkg-footer -->
<div class="clearfix">
<?php include "footer.php"; ?>
</div>
