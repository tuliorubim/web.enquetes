<?php
include "criar_enquete_modelo.php";
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
	<div class="col-md-8 col-md-offset-2">
	<?php
	$POST = $_POST;
	$FILES = $_FILES;
	$SESSION = $_SESSION;
	$ide = $POST["idEnquete"];
	$status = '';
	if (strlen($POST['tempo_teste']) > 0)
		$POST['tempo_teste'] .= ':00';
	$valid = $we->valida_enquete2();
	
	$we->select("select cd_usuario from enquete where idEnquete = $ide", array("cdu"));
	if (is_bool($valid) && ($ide == NULL || $cdu == $we->idu)) {
		$we->formTabela1 = Dados_webenquetes::$formTabela1;
		$we->formTabela2 = Dados_webenquetes::$formTabela2;
		$we->formTabela3 = Dados_webenquetes::$formTabela3;
		$we->formTabela4 = Dados_webenquetes::$formTabela4;
		$we->formTabela5 = Dados_webenquetes::$formTabela5;
		if ($POST['del'] == "Excluir Pergunta") $POST['butPres'] = NULL;
		$edit = true;
		if ($POST['butPres'] === 'Delete' && $ide !== NULL) {
			$we->delete_enquete($ide);
			$edit = false;
		} else {
			$we->crud_enquete($ide);
			if ($ide == NULL) $we->select("select max(idEnquete) from enquete where cd_usuario = $we->idu", array('ide'));
			$we->crud_logo($cd_servico);
		}
		
		$we->crud_pergunta_respostas($ide);
		
		if ($POST['aceitar_termos'] === 'on') {
			mysqli_query($we->con, "update cliente set aceito = 1 where idCliente = ".$_SESSION[$we->idSession]);
		}
		if ($edit) {
			$we->editing_messages($ide);
		} 
	} elseif (is_string($valid)) {
		$status = $valid;
		$we->write_status();
	}
	?>
	</div>
	</div>
</div>
<div class="bkg-enquetes-h">
<?php include "latest_polls.php"; ?>
</div>

<?php include "footer.php"; ?>
</body>
