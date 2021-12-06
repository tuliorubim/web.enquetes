<?php
include "criar_conteudo_modelo.php";
$idSession = 'user';
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php include "head.php"; ?>
<body>
<?php 
include "header.php"; 
?>
<div class="container">
    <div class="bkg-menu-cliente">
	<div class="row">
    <div class="col-md-8">
    <?php include 'menu_cliente.php'; ?>
    </div>
    </div>
    </div>
    <div class="row">
	<form name='form' method='post' action="criar_enquete_controle.php" enctype='multipart/form-data'>
	<div class="col-md-6">
    <h2>CRIE SEU CONTE&Uacute;DO</h2>
	<div class="fb-like" data-href="https://www.facebook.com/WebEnquetesEPesquisas/" data-width="100" data-layout="standard" data-action="like" data-size="small" style='margin: 3px;' data-show-faces="true" data-share="false">
	</div>
	<?php
	$we->valida_conteudo1();
	$status = $service->get_free_months();
	if ($service->status == '') {
		$status = $service->change_status();
	}
	if ($status != '') {
		echo "<p><span class='status2'>$status</span></p>";
	}
	$idConteudo = $we->create_content();
	if (!isset($idConteudo)) $idConteudo = $_GET['idcont'];
	$idc = (isset($_POST['cd_categoria'])) ? $_POST['cd_categoria'] : $_GET['idc'];
	require_once "create_html.php";
	$design = new Create_HTML(0, $idConteudo);
	$design->con = $we->con;
	$design->idu = $we->idu;
	$design->select("select cd_usuario from conteudo where idConteudo = $idConteudo", array("cdu"));
	if ($idConteudo === NULL || $cdu == $design->idu) {
		$design->formTabela1 = Dados_webenquetes::$formTabela1;
		$design->formTabela5 = Dados_webenquetes::$formTabela5;
		$design->formTabela7 = Dados_webenquetes::$formTabela7;
		$design->formTabela8 = Dados_webenquetes::$formTabela8;
		$design->formTabela9 = Dados_webenquetes::$formTabela9;
		$design->formTabela10 = Dados_webenquetes::$formTabela10;
		$design->formTabela11 = Dados_webenquetes::$formTabela11;
		
		$inds = $design->form_categorias();
		$inds = $design->form_conteudo($inds);
		$inds = $design->upload_ad($inds, $cd_servico);
		$inds = $design->form_content_type($inds);
		$inds = $design->form_conteudo2($inds);
	}
	?>
	</div>
	</form>
	
	</div>
</div> 
<div class="bkg-enquetes-h">
<?php include "latest_polls.php"; ?>
</div>

<?php include "footer.php"; ?>

</body>
</html>
