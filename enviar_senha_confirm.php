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
        
        <!-- INICIO / AQUI É O ESPAÇO AONDE VOCÊ IRA COLOCAR O CONTÉUDO DAS OUTRAS PAGINAS INTERNAS QUE VAI APARECER NA COLUNA ESQUERDA -->
    <?php
	class SendPassword extends AdminFunctions {
		public function __construct($con) {
			$this->con = $con;
		}
		public function enviar_senha() {
			if ($_POST['send'] === 'Enviar') {
				$email = mysqli_real_escape_string($this->con, $_POST["email"]);
				if (!empty($email)) {
					$POST = $_POST;
					$sql = "select senha from cliente where usuario = '$email'";
					$arg = $this->select($sql);	
					$senha = $arg[0][0];
					if (!empty($senha)) {
						$POST['email'] = "tfrubim@gmail.com";
						$POST['name'] = "Web Enquetes";
						$POST['subject'] = "Sua senha na Webenquetes";
						$POST['message'] = "A senha da sua conta na Web Enquetes &eacute;: $senha";
						$this->sendEmail ($POST, array($email));
						$status = "Senha enviada com sucesso.";
						$this->write_status();
					} else echo "<font color=red><b>Este e-mail n&atilde;o foi encontrado. Verifique se voc&ecirc; o digitou corretamente.</b></font>";
				}
			}
		}
	}
	$adm = new SendPassword($con);
	$adm->enviar_senha();
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