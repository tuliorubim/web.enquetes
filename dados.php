<?php
include "dados_modelo.php";
//require_once "funcoes/funcoesDesign.php";
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
    <h2>MEUS DADOS</h2>
	<form name='form' method='post' action="dados_controle.php" enctype='multipart/form-data'>
	<?php
	$status = $service->get_free_months();
	if ($service->status == '') {
		$status = $service->change_status();
	}
	if ($status != '') {
		echo "<p><span class='status2'>$status</span></p>";
	}
	class FormCliente extends DesignFunctions {
		use Dados_webenquetes;
		public $idu;
		public function __construct($idu, $con) {
			$this->idu = $idu;
			$this->con = $con;
		}
		public function form_cliente() {
			$sql = "select idCliente, nome, empresa, site, logo, logoReduzida, data_cadastro, usuario from cliente where idCliente = $this->idu";
			$select = array($sql, "form");
			if ($this->idu !== 0) {
				$select[5] = true;
			}
			
			$this->formGeral ($_SESSION, $this->formTabela6, array(), array(), $select, true);
		}

	}
	$design = new FormCliente($we->con, $we->idu);
	$design->formTabela6 = Dados_webenquetes::$formTabela6;
	$design->form_cliente();
	?>
	<br>
	<input type='hidden' name='butPres' value=''/>
	<input type='button' class="btn btn-primary estilo-modal" name='salvar' value='Salvar'/>
	</form>
	<script language="javascript">
	var conditions = [];
	conditions['usuario'] = 'email';
	conditions['senha'] = 8;
	$(document).ready(function () {
		$("input[name='salvar']").click(function () {
			if (document.form.site.value.length > 0) 
				conditions['site'] = 'url';
			valid = validateForm(document.form, conditions);
			if (valid) ValidaGravacao();
		});
	});
	</script>
    </div>
    </div>
</div>
<div class="bkg-enquetes-h">
<?php include "latest_polls.php"; ?>
</div>

<?php include "footer.php"; ?>

</html>
