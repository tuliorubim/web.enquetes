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
	class AdminUser extends AdminFunctions {
		use Dados_webenquetes2;
		public function __construct($con) {
			$this->con = $con;
		}
		public function valida_cliente () {
			$conditions = array();
			$conditions['usuario'] = 'email';
			if (strlen($_POST['site']) > 0) $conditions['site'] = 'url';
			return $this->validateForm($_POST, $conditions);
		}
		public function crud_cliente() {
			$POST = $_POST;
			if (!empty($_FILES['logo']['name'])) {
				$POST['logoReduzida'] = $_FILES['logo'];
				$POST['logoReduzida']['name'] = 'thumb'.$POST['logoReduzida']['name'];
			}
			if (empty($POST['data_cadastro'])) {
				$POST['data_cadastro'] = date("d/m/Y H:i:s");
			} 
			$this->adminPage ($POST, $_FILES, $_SESSION, $this->formTabela6, array(), array(), true);
			$this->write_status('status');
		}

	}
	$cdu = $_POST['idCliente'];
	$adm = new AdminUser($we->con);
	$valid = $adm->valida_cliente();
	if (is_bool($valid) && ($cdu === NULL || $cdu == $we->idu)) {
		$adm->formTabela6 = Dados_webenquetes2::$formTabela6;
		$adm->crud_cliente();
	} elseif (is_string($valid)) {
		$status = $valid;
		$adm->write_status();
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
