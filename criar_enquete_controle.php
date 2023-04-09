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
	class AdminPoll extends AdminFunctions {
		use Dados_webenquetes;
		public function __construct($con, $ft1, $ft2, $ft3, $ft4, $ft5) {
			$this->con = $con;
			self::$formTabela1 = $ft1;
			self::$formTabela2 = $ft2;
			self::$formTabela3 = $ft3;
			self::$formTabela4 = $ft4;
			self::$formTabela5 = $ft5;
		}
		public function valida_enquete2 () {
			$conditions = array();
			$conditions["categoria"] = 1;
			$conditions["enquete"] = 1;
			$conditions["pergunta"] = 1;
			$conditions["resposta1"] = 1;
			if (isset($_POST["aceitar"])) {
				$conditions["aceitar"] = '/^(true)$/';
			}
			if (isset($_POST["resposta0"])) {
				$conditions["resposta0"] = 1;
			} else $conditions["resposta2"] = 1;
			return $this->validateForm($_POST, $conditions);
		}
		public function delete_enquete($ide) {
			global $status;
			global $POST;
			$con = $this->con;
			mysqli_query($con, "delete from voto where cd_enquete = $ide");
			mysqli_query($con, "delete from resposta where cd_pergunta in (select idPergunta from pergunta where cd_enquete = $ide)");
			mysqli_query($con, "delete from pergunta where cd_enquete = $ide");
			mysqli_query($con, "delete from enquete where idEnquete = $ide");
			$POST['butPres'] = NULL;
			$status = "Sua enquete foi exclu&iacute;da corretamente.";
			$class = 'status';
			$this->write_status($class);
		}
		public function crud_enquete($ide) {
			global $POST;
			global $status;
			$dateformat = $this->dateformat;
			$timeformat = $this->timeformat;
			if ($POST['butPres'] === 'Save') {
				$POST["cd_usuario"] = $_SESSION[$this->idSession];
				$POST["cd_categoria"] = $_POST["idCategoria"];
				if ($ide == -1) $POST["dt_criacao"] = date("$dateformat $timeformat");
				if ($POST['code'] == NULL) $POST['code'] = $this->codeGenerator();
				if ($POST['acima_de_cem'] == NULL) $POST['acima_de_cem'] = 0;
				if ($POST['tempo_teste'] == NULL) $POST['tempo_teste'] = 0;
				if (!isset($POST["enquete_ou_prova"])) {
					$POST['enquete_ou_prova'] = $_POST['enq_ou_prova'];
				} 
				$POST["disponivel"] = 1;
				if ($ide != -1) mysqli_query($this->con, "update poll_html set mudou = 1 where cd_enquete = $ide and is_poll = true");
			}
			$this->adminPage ($POST, $_FILES, $_SESSION, self::$formTabela2, array(), array());
			$class = 'status';
			if (strpos ($status, "sucesso") !== FALSE) {
				if (strpos ($status, "salvo") !== FALSE) {
					$status = "Sua enquete foi criada ou atualizada corretamente.";
			?>
					<!-- Event snippet for Criaï¿?ï¿?o de enquetes conversion page -->
					<script>
					  //gtag('event', 'conversion', {'send_to': 'AW-948860159/iIFeCKyV-_MBEP_pucQD'});
					</script>
			<?php
				} 
			} 
			$this->write_status($class);
		}
		public function crud_logo($cd_servico) {
			global $POST;
			if ($cd_servico > 0) {
				if (!empty($_FILES['logo']['name'])) {
					$POST['logoReduzida'] = $_FILES['logo'];
					$POST['logoReduzida']['name'] = 'thumb'.$POST['logoReduzida']['name'];
				}
				$this->adminPage ($POST, $_FILES, $_SESSION, self::$formTabela5, array(), array());
			}
		}
		public function crud_pergunta_respostas($ide) {
			global $POST;
			global $status;
			$con = $this->con;
			if ($POST['del'] == "Excluir Pergunta" && !empty($POST['idPergunta'])) {
				$POST['butPres'] = $_POST['butPres'];
				mysqli_query($con, "delete from voto where cd_pergunta = ".$POST['idPergunta']);
			}
			if (!empty($POST['idPergunta'])) {
				$rs = mysqli_query($con, "select idResposta from resposta where cd_pergunta = ".$POST['idPergunta']);
				$er = $this->ValorSelecionado ($POST, $rs, "idResposta", "delete", "Delete");
				if ($er[1]) {
					mysqli_query($con, "delete from voto where cd_resposta = ".$er[0]);
				}
			}
			$POST['cd_enquete'] = $ide;
			if ($POST["multipla_resposta"] == NULL || $POST["multipla_resposta"] == false) {
				$POST["multipla_resposta"] = 0;
			}
			unset(self::$formTabela4[0][4]);
			
			$this->adminPage ($POST, $_FILES, $_SESSION, self::$formTabela3, self::$formTabela4, array());
			
			if ($POST['enquete_ou_teste'] == '1') {
				if (isset($POST['cd_resposta'])) {
					if ($POST['idPergunta'] == NULL) {
						$limit = (int) $POST['cd_resposta'];
						$arg1 = $this->select("select max(idPergunta) from pergunta where cd_enquete = $ide");
						$arg2 = $this->select("select max(idResposta) from (select idResposta from resposta where cd_pergunta = ".$arg1[0][0]." order by idResposta limit $limit) r");
						mysqli_query($con, "update pergunta set cd_resposta_certa = ".$arg2[0][0]." where idPergunta = ".$arg1[0][0]);
					} else {
						$limit = ((int) $POST['cd_resposta'])+1;
						$arg2 = $this->select("select max(idResposta) from (select idResposta from resposta where cd_pergunta = ".$POST['idPergunta']." order by idResposta limit $limit) r");
						if ($POST['cd_resposta_certa'] != $arg2[0][0])
							mysqli_query($con, "update pergunta set cd_resposta_certa = ".$arg2[0][0]." where idPergunta = ".$POST['idPergunta']);
					}
				} else $status = "Voc&ecirc; n&atilde;o especificou uma reposta certa para este teste.";
			}
			$class = 'st';
			if ($POST['enquete_ou_prova'] != $_POST['enq_ou_prova']) {
				if ($_POST['enq_ou_prova'] != '3') {
					$enquetes = $this->select("select count(idPergunta) from pergunta where cd_enquete = $ide and cd_resposta_certa = 0");
					$testes = $this->select("select count(idPergunta) from pergunta where cd_enquete = $ide and cd_resposta_certa > 0");
					$enquetes = $enquetes[0][0];
					$testes = $testes[0][0];
					$aux = '';
					if ($enquetes > 0 && $testes > 0) {
						switch ($_POST['enq_ou_prova']) {
							case '1' :
								$status = "N&atilde;o &eacute; poss&iacute;vel mudar o seu question&aacute;rio para somente enquetes, pois ele cont&eacute;m testes e enquetes."; 
								break;
							case '2' :
								$status = "N&atilde;o &eacute; poss&iacute;vel mudar o seu question&aacute;rio para somente testes, pois ele cont&eacute;m testes e enquetes."; 
								break;
						}
					} elseif ($enquetes > 0) {
						switch ($_POST['enq_ou_prova']) {
							case '1' :
								mysqli_query($this->con, "update enquete set enquete_ou_prova = 1 where idEnquete = $ide");
								$status = "Seu question&aacute;rio tornou-se question&aacute;rio apenas de enquetes com sucesso."; 
								$class = 'status';
								break;
							case '2' :
								$status = "N&atilde;o &eacute; poss&iacute;vel mudar o seu question&aacute;rio para somente testes, pois ele cont&eacute;m somente enquetes."; 
								break;
						}
					} elseif ($testes > 0) {
						switch ($_POST['enq_ou_prova']) {
							case '2' :
								mysqli_query($this->con, "update enquete set enquete_ou_prova = 2 where idEnquete = $ide");
								$status = "Seu question&aacute;rio tornou-se question&aacute;rio apenas de testes com sucesso."; 
								$class = 'status';
								break;
							case '1' :
								$status = "N&atilde;o &eacute; poss&iacute;vel mudar o seu question&aacute;rio para somente enquetes, pois ele cont&eacute;m somente testes."; 
								break;
						}
					}
				} else {
					mysqli_query($this->con, "update enquete set enquete_ou_prova = 3 where idEnquete = $ide");
					$status = "Seu question&aacute;rio tornou-se question&aacute;rio misto com sucesso.";		
					$class = 'status';
				}
			}
			if (!empty($status)) $status .= "<br><br>";
			$this->write_status($class);
			$status = '';
			if (strpos ($status, "sucesso") !== FALSE) {
				if (strpos ($status, "salvo") !== FALSE) {
					$status = "Pergunta foi criada ou atualizada corretamente.";
				} elseif (strpos ($status, "resposta") !== FALSE) {
					$status = "Resposta foi exclu&iacute;da corretamente.";
				} elseif (strpos ($status, "exclu&iacute;do") !== FALSE) {
					$status = "Pergunta foi exclu&iacute;da corretamente.";
				}
			}
			$class = 'status';
			$this->write_status($class);
		}
		public function editing_messages($ide) {
			global $service_data;
			echo "<br><br><br>";
		?>
			<p><a href="criar_enquete.php?ide=<?php echo $ide;?>&np=true">Voltar e editar ou criar nova pergunta para a enquete.</a></p>
			<br>
			<p><a href="enquete.php?ide=<?php echo $ide;?>">Ir para a enquete.</a></p>
			<br>
			<p><a href="divulgar.php">Conhe&ccedil;a nossas ferramentas para divulgar enquetes.</a></p>
		<?php 
			if (empty($service_data)) {
		?>
				<p style="background-color:#FFFF99; padding:10px; border-radius:5px;">
		<?php
				echo htmlentities("Você pode adquirir gratuitamente benefícios como exibir um anúncio na sua enquete, esconder resultados parciais, baixar resultados parciais inteiros ou por grupos em PDF, além de outros benefícios. Saiba mais e adquira a assinatura gratuita clicando ", ENT_NOQUOTES, 'ISO-8859-1', true);
		?>
				<a href="bonus_mensais.php" target="_blank">aqui</a>.</p>
		<?php
			}
		}

	}
	$POST = $_POST;
	$FILES = $_FILES;
	$SESSION = $_SESSION;
	$ide = (!empty($POST["idEnquete"])) ? $POST['idEnquete'] : -1;
	$status = '';
	//if (strlen($POST['tempo_teste']) > 0)
		$POST['tempo_teste'] = '00:00:00';
	$ft1 = Data_webenquetes::$formTabela1;
	$ft2 = Data_webenquetes::$formTabela2;
	$ft3 = Data_webenquetes::$formTabela3;
	$ft4 = Data_webenquetes::$formTabela4;
	$ft5 = Data_webenquetes::$formTabela5;
	$adm = new AdminPoll($we->con, $ft1, $ft2, $ft3, $ft4, $ft5);
	$valid = $adm->valida_enquete2();
	$adm->select("select cd_usuario from enquete where idEnquete = $ide", array("cdu"));
	if (is_bool($valid) && ($ide == -1 || $cdu == $we->idu)) {
		if ($POST['del'] == "Excluir Pergunta") $POST['butPres'] = NULL;
		$edit = true;
		if ($POST['butPres'] === 'Delete' && $ide !== -1) {
			$adm->delete_enquete($ide);
			$edit = false;
		} else {
			$adm->crud_enquete($ide);
			if ($ide == -1) $we->select("select max(idEnquete) from enquete where cd_usuario = $we->idu", array('ide'));
			$adm->crud_logo($cd_servico);
		}
		
		$adm->crud_pergunta_respostas($ide);
		if ($POST['aceitar_termos'] === 'on') {
			mysqli_query($adm->con, "update cliente set aceito = 1 where idCliente = ".$_SESSION[$adm->idSession]);
		}
		if ($edit) {
			$adm->editing_messages($ide);
		} 
	} elseif (is_string($valid)) {
		$status = $valid;
		$adm->write_status();
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
