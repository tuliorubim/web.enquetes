<?php
require_once "funcoes/funcoesAdministrativas.php";
require_once "dados_webenquetes.php";
class Webenquetes extends AdminFunctions {
	use Dados_webenquetes;
	private const IDMYPOLL = 10008;
	public $con;
	public $logged_in = false;
	public $fw = '640px';
	public $fh = '520px';
	public $idu;
	public $nome;	
	public $usuario;
	public $title;
	public $description;
	
	public function Connect_WE () {
		//$this->con = $this->Connect('localhost', 'webenque_enquetes', 'root', '5hondee5WBa0');
		$this->con = $this->Connect('localhost', 'webenque_enquetes', 'root', ''); 
		$con = $this->con;
		mysqli_query($con, "SET NAMES 'utf8'");
		mysqli_query($con, 'SET character_set_connection=utf8');
		mysqli_query($con, 'SET character_set_client=utf8');
		mysqli_query($con, 'SET character_set_results=utf8');
	}
	public function create_session () {
		$idSession = $this->idSession;
		$cookie_url = $this->cookie_url; 
		$this->cookie_https = FALSE;
		$cookie_https = $this->cookie_https;
		/*$this->cookie_url = "webenquetes.com.br";
		$cookie_url = $this->cookie_url; 
		$cookie_https = $this->cookie_https;*/
		if ($_GET['login'] === "off") {
			setcookie($idSession, '', time()-10, '/', $cookie_url, $cookie_https);
			$this->logout();
		}
		if ($_SESSION[$idSession] !== NULL && $_SESSION[$idSession] !== $_COOKIE[$idSession]) {
			setcookie($idSession, $_SESSION[$idSession], time()+(86400*365*10), '/', $cookie_url, $cookie_https);
		}
		if ($_COOKIE[$idSession] !== NULL && $_SESSION[$idSession] !== $_COOKIE[$idSession]) {
			$_SESSION[$idSession] = $_COOKIE[$idSession];	
		}
		if ($_GET['login'] === "off") {
			$_SESSION[$idSession] = NULL;
		}
		$idu = $this->idu;
		$idu = $_SESSION[$idSession];
		if ($idu === NULL) $idu = 0;
		if (strpos($_SERVER['REQUEST_URI'], 'minhas_enquetes.php') !== FALSE) {
			$user = $this->select("select usuario from cliente where idCliente = $idu");
			$user = $user[0][0];
			if ($idu === 0 || ($idu !== 0 && $user == NULL)) {
				$this->login($_POST, 'cliente', array('usuario', 'senha', 'enter'));
				$idu = $_SESSION[$idSession];
			} 
		}
		$usuario = $this->select("select nome, usuario from cliente where idCliente = $idu");
		$this->nome = $usuario[0][0];
		$this->usuario = $usuario[0][1];
		$this->idu = $idu;
		if ($_SESSION[$idSession] !== NULL && !empty($this->usuario)) $this->logged_in = true;
	}
	public function enquetes_mais_procuradas() {
		$enquetes = $this->select("select e.idEnquete, e.enquete from enquete e inner join voto v on e.idEnquete = v.cd_enquete where esconder = 0 group by e.idEnquete having count(v.dt_voto) > 30 order by e.dt_criacao desc limit 6");
		return $enquetes;
	}
	public function cria_enquete () {
		global $status;
		$POST = $_POST;
		$idSession = $this->idSession;
		$dateformat = $this->dateformat;
		$timeformat = $this->timeformat;
		$variaveis = array("idEnquete", "cd_categoria", "cd_usuario", "enquete", "introducao", "dt_criacao");
		$tipos = array('integer', 'integer', 'integer', 'varchar', 'varchar', 'datetime');
		$tabela = "enquete";
		$POST["dt_criacao"] = date("$dateformat $timeformat");
		if (!$this->logged_in) {
			$email = $POST['usuario'];
			$senha = $POST['senha'];
			$email1 = mysqli_real_escape_string($this->con, $POST['usuario']);
			$senha1 = mysqli_real_escape_string($this->con, $POST['senha']);
			$res = $this->select("select idCliente from cliente where usuario = '$email1' and senha = '$senha1'");
			$idCliente = $res[0][0];
			$POST['introducao'] = '';
			if ($idCliente == NULL) {
				$res = $this->select("select usuario from cliente where usuario = '$email1'");
				$this->usuario = $res[0][0];
				if ($this->usuario != $email) {
					//include "sendmail.php";
					$variaveis1 = array("idCliente", "data_cadastro", "usuario", "senha");
					$tipos1 = array("integer", "datetime", "varchar", "varchar");
					$enderecos1 = array();
					$tabela1 = "cliente";
					$POST["data_cadastro"] = date("$dateformat $timeformat"); 
					if ($_SESSION[$idSession] !== NULL) {
						$POST["idCliente"] = $_SESSION[$idSession];
						setcookie($idSession, '', time()-10, '/', $cookie_url, $cookie_https);
					}
					$ret = $this->save($tabela1, $variaveis1, $tipos1, $POST, $enderecos1, -1);
					$POST['email'] = "tfrubim@gmail.com";
					$POST['name'] = "Web Enquetes";
					$POST['subject'] = "Sua senha Web Enquetes";
					$POST['message'] = "A senha do usuário que você acabou de criar na Web Enquetes é \n\n".$POST['senha'].".";
					//$this->sendEmail ($POST, array($email));
					if ($_SESSION[$idSession] === NULL) $_SESSION[$idSession] = $ret[1];
					$this->usuario = $email;
					$this->logged_in = true;
					$POST['cd_usuario'] = $ret[1];
					$ret2 = $this->save($tabela, $variaveis, $tipos, $POST, array(), -1);
					$idEnquete = $ret2[1];
					$status = '';
					echo "<script>novo_usuario = true;</script>";
				} else {
					$status = "A senha est&aacute; incorreta";
				}
			} else {
				$POST['cd_usuario'] = $idCliente; 
				$ret = $this->save($tabela, $variaveis, $tipos, $POST, array(), -1);
				$idEnquete = $ret[1];
				$this->login($POST, 'cliente', array('usuario', 'senha', 'button'));
			}
		} else {
			$POST['cd_usuario'] = $_SESSION[$idSession]; 
			$ret = $this->save($tabela, $variaveis, $tipos, $POST, array(), -1);
			$idEnquete = $ret[1];
		}
		echo "<script language='javascript'>$('#status').html('<font color=red>$status</font>');</script>";
		return $idEnquete;
	}
	public function new_user() {
		$dateformat = $this->dateformat;
		$timeformat = $this->timeformat;
		$idSession = $this->idSession;
		if ($_SESSION[$idSession] != NULL) {
			$this->idu = $_SESSION[$idSession];
		}
		$data = date("$dateformat $timeformat");
		if (empty($this->idu)) {
			$this->save('cliente', array('data_cadastro', 'ip'), array('datetime', 'varchar'), array($data, $_SERVER['REMOTE_ADDR']));
			$lu = $this->select("select max(idCliente) from cliente");
			$last_user = $lu[0][0];
			$_SESSION['user'] = $last_user;
			$this->idu = $last_user;
		}	
	}
	private function poll_keywords($ide) {
		$args = $this->select("select e.enquete, e.introducao, e.cd_usuario, e.disponivel, p.pergunta, r.resposta from enquete e left join pergunta p on e.idEnquete = p.cd_enquete left join resposta r on p.idPergunta = r.cd_pergunta where e.idEnquete = $ide");
		$result = '';
		if ($args[0]['disponivel'] /*&& ($args[0]['cd_usuario'] == 1 || $args[0]['cd_usuario'] == 55291)*/) {
			$result = $args[0]['enquete'].' '.$args[0]['introducao'].' ';
			for ($i = 0; $args[$i]['enquete'] !== NULL; $i++) {
				if ($args[$i]['pergunta'] != $args[$i-1]['pergunta']) {
					$result .= $args[$i]['pergunta'].' ';
				}
				$result .= $args[$i]['resposta'].' ';
			}
		}
		return $result;
	}
	public function set_title_and_keywords() {
		$title = htmlentities("Adquira o CONHECIMENTO que você deseja criando e divulgando enquetes de maneira FÁCIL e RÁPIDA agora mesmo!", ENT_NOQUOTES, 'ISO-8859-1', true);
		$description = htmlentities("criar, fazer, elaborar, modelo, site, pergunta, perguntas, formulário, questionário, inquérito, enquete, pesquisa, mercado, satisfação, opinião, política, esportes, religião, atualidades, ciência, economia, entretenimento, filmes, jogos, livros, música, televisão, internet, informática, cliente, funcionário, interno", ENT_NOQUOTES, 'ISO-8859-1', true);
		if ($_GET['ide'] !== NULL) {
			$ide = $_GET['ide'];
			$description = $this->poll_keywords($ide);
			$title = "Enquete: ".substr($description, 0, strpos($description, ' ', 130)).'...';
			$args = $this->select("select esconder from enquete where idEnquete = $ide");
			if (!$args[0]['esconder']) {
				$desc = explode(' ', $description);
				$description = '';
				$i = 0;
				$j = 0;
				while ($j < 80 && is_string($desc[$i])) {
					$last = strlen($desc[$i])-1;
					if (!in_array($desc[$i][$last], array(',', '.', ';', '?', '!'))) {
						if (!preg_match('/^[(ao?s?)(com)(de)(d?|n?a|os?)(em?)(às?)]$/', strtolower($desc[$i]))) {
							$description .= $desc[$i].', ';
							$j++;
						}
					} elseif (!preg_match('/^[(ao?s?)(com)(de)(d?|n?a|os?)(em?)(às?)]$/', strtolower(substr($desc[$i], 0, $last)))) {
						$description .= substr($desc[$i], 0, $last).', ';	
						$j++;
					}
					$i++;
				}
			} else $description = '';
		}
		$this->title = $title;
		$this->description = $description;
	}
	public function addIP() {
		$visitas = $this->select("select count(idCliente) from cliente");
		if ($visitas[0][0]%10 == 0) {
			$dest = 'ipsss.txt';
			$IPs = $this->open_file($dest);
			$IPs2 = $this->select("select ip from cliente where ip <> '' group by ip having count(idCliente) > 50 order by max(data_cadastro) desc");
			$content = '';
			for ($i = 0; $IPs2[$i]['ip'] != NULL; $i++) {
				$content .= " ".$IPs2[$i]['ip'];
			}
			$content = substr($content, 1);
			if (!empty($content)) { 
				/*$aux = strpos($IPs, ' array');
				$p = strpos($IPs, '(', $aux)+1;
				$cont = strpos($IPs, ')', $p)-$p;
				$search = substr($IPs, $p, $cont);
				//echo "$search<br><br>$content";*/
				if ($IPs != $content) {
					//$IPs = str_replace($search, $content, $IPs);
					$this->save_file($content, $dest, 'w');
				}
			}
		}
	}
	public function my_poll() {
		$idMypoll = self::IDMYPOLL;
		$has_voted = $this->select("select * from voto where cd_enquete = $idMypoll and cd_usuario = $this->idu");
		if (empty($has_voted) && empty($this->usuario)) {
		?>
		<div class="container">
			<div class="row">
			<div class="col-md-12">
				<div class="modal fade enter_email" tabIndex=-1 role="dialog" aria-labelledby="mySmallModalLabel">
						
					<button type="button" class="close" id="close1" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<form name="enter_email" method="post">
						<input type="hidden" name="butPres">
						<input type="hidden" name="idEnquete" id="idEnquete">
						<p><?php echo $this->html_encode("Considere criar enquetes para esse fim. Deixe conoso seu e-mail se você gostaria de receber notícias sobre nossos serviços para enquetes:");?>
						<input type="text" name="email" placeholder="Seu email" size="35" maxlength="128" />
						</p>
						<p><input type="button" class="btn btn-primary estilo-modal" name="enviar1" id="enviar1" value="Enviar"></p>
					</form>
				</div>
				<div style="position:fixed; z-index:1; bottom:0%; color:#FFFFFF; background-color:#000000; opacity:80%; font-size:18px; padding:10px; display:none;" id="target-public"><button type="button" class="close" aria-label="Close" onclick="document.getElementById('target-public').style.display='none'"><span aria-hidden="true">&times;</span></button>
				<?php
				$mypoll = $this->select("select p.idPergunta, p.pergunta, r.idResposta, r.resposta from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta where p.cd_enquete = $idMypoll");
				echo "<input type='hidden' name='idPergunta' value='".$mypoll[0][0]."'>";
				echo $mypoll[0][1];
				for ($i = 0; $mypoll[$i][2] !== NULL; $i++) {
					echo "&nbsp;&nbsp;&nbsp;<button name='answer$i' id=".$mypoll[$i][2]." class='btn btn-primary estilo-modal'>".$mypoll[$i][3]."</button>";
				}
				?>
				&nbsp;&nbsp;
				</div>
			</div>
			</div>
		</div>
		<script language="javascript">
		//document.getElementById("target-public").style.marginTop = window.document.body.clientHeight-280;
		setTimeout(function () {$("#target-public").fadeIn(1000);}, 4000);
		$("button").click(function () {
			answer = ['answer0', 'answer1'];
			isto = $(this);
			if (answer.indexOf($(this).attr('name')) != -1) {
				$.ajax({
					url: 'voto.php',
					type: 'GET',
					dataType: 'jsonp',
					data: {
						cd_enquete: <?php echo $idMypoll;?>,
						cd_pergunta: $("input[name='idPergunta']").val(),
						cd_resposta: $(this).attr("id")
					},
					success: function (result) {
						$("#target-public").css("display", "none");
						if (result['status'].indexOf("sucesso") != -1) {
							if (isto.attr('name') == 'answer0') {
								$(".enter_email").modal();
							}
						} 
					},
					error: function (xhr, s, e) {
						alert(xhr.responseText);
					}
				});
			}
		});
		cdu = <?php echo (isset($this->idu)) ? $this->idu : 0;?>;
		$("#enviar1").click(function () {
			$.ajax({
				url: "criar_usuario.php",
				type: "POST",
				dataType: "json",
				data: {
					cdu: cdu,
					email: document.enter_email.email.value
				},
				success: function (result) {
					if (result['status'] == "sucesso") {
						document.getElementById("close1").click();
						alert("Voc\u00ea acaba de cadastrar seu email e uma senha foi gerada e enviada para este email para que voc\u00ea tenha acesso \u00e0 \u00e1rea restrita deste site.");
					}
				},
				error: function (xhr, s, e) {
					alert(xhr.responseText);
				}
			});
		});
		</script>
<?php 
		} 
	}
	public function listar_categorias () {
		$args = $this->select("select idCategoria, categoria from categoria order by categoria");
		for ($i = 0; $args[$i][1] !== NULL; $i++) {
			echo "<option value=".$args[$i][0].">".$args[$i][1]."</option>";
		}
	}
	public function search_polls() {
		echo "<h1>";
		$idc = $_GET['idc'];
		$pc = NULL;
		if (is_string($_GET['procurar'])) {
			$pc = mysqli_real_escape_string($this->con, $_GET['procurar']);
		}
		$modelo = $_GET['m'];
		if ($idc !== NULL) {
			$res = $this->select("select categoria from categoria where idCategoria = $idc");
			$categoria = $res[0][0];
			echo "Enquetes da categoria \"$categoria\"";
		} elseif (is_string($pc)) {
			echo "Resultado da busca.";
		} elseif ($modelo == 'true') {
			echo "Enquetes modelo";
		}
		else echo "Todas as enquetes";
		echo "</h1>";
		$sql = "select idEnquete, enquete, dt_criacao from enquete where disponivel = 1 and esconder = 0";
		if ($idc !== NULL) {
			$sql .= " and cd_categoria = $idc";
		} elseif (is_string($pc)) {
			echo $this->procura ($pc, array('divulgar.php', 'no_seu_site.php'), array('enquete.php?ide=', 'resultados_parciais.php?ide='), array("select e.idEnquete, e.enquete, e.introducao, p.pergunta, r.resposta from enquete e inner join pergunta p on e.idEnquete = p.cd_enquete inner join resposta r on p.idPergunta = r.cd_pergunta where e.idEnquete not in (select idEnquete from enquete where esconder = 1)", "select cd_enquete, comentario from comentario where cd_enquete not in (select idEnquete from enquete where esconder = 1)"));
		} elseif ($modelo == 'true') {
			$sql .= " and (cd_usuario = 1 or cd_usuario = 55436 or cd_usuario = 55291)";
			echo "<p>".htmlentities("Nas enquetes abaixo, procuramos elaborar opções de respostas que tentam abrangir todo o universo de possíveis respostas às suas respectivas perguntas, sabendo que responder a uma enquete é escolher a resposta que mais se aproxima do caso de quem a responde. Estude as enquetes abaixo e observe os vários aspectos de uma enquete bem elaborada, se você achar que isso é necessário para você criar sua enquete.", ENT_NOQUOTES, 'ISO-8859-1', true)."</p><br>";
		}
		if (!is_string($pc)) {
			$sql .= " order by idEnquete desc";
			$args = $this->select($sql);
			for ($i = 0; $args[$i][0] !== NULL; $i++) {
				echo "<p><div class='nome_enquete'><a href='enquete.php?ide=".$args[$i][0]."'>".$args[$i][1]."</a></div><div class='nome_enquete2'>Criada em: ".$this->std_date_create($args[$i][2])."</div></p>";
			}
		}
	}
	public function processa_voto_remoto($idEnquete) {
		$idu = $this->idu;
		$con = $this->con;
		$tabela = "voto";	
		$i = 1;
		$data = date('Y-m-d H:i:s');
		$args = $this->select("select cd_usuario, cd_enquete from $tabela where cd_usuario = $idu and cd_enquete = $idEnquete limit 1");
		$cdu = $args[0]['cd_usuario'];
		$cde = $args[0]['cd_enquete'];
		$condicao = ($cdu !== NULL && $cde !== NULL);
		if ($condicao) {
			mysqli_query($con, "delete from $tabela where cd_usuario = $cdu and cd_enquete = $cde");
		}
		$sucesso = false;
		if ($idu === 0) {
			mysqli_query($con, "insert into cliente (data_cadastro) values ('$data')");
			$last_user = $this->select("select max(idCliente) from cliente");
			$_SESSION[$idSession] = $last_user[0][0];
			$idu = $last_user[0][0];
		}
		while ($_POST["idPergunta$i"] !== NULL) {
			$j = 0;
			$idP = $_POST["idPergunta$i"];
			$mr = $this->select("select multipla_resposta from pergunta where idPergunta = $idP");
			if ($mr[0][0] == 0) {
				$idR = $_POST["resposta".$i."_"];
				if (!empty($idR)) {
					$sql = "insert into $tabela (cd_usuario, cd_enquete, cd_pergunta, cd_resposta, dt_voto) values ($idu, $idEnquete, $idP, $idR, '$data') ";
					mysqli_query($con, $sql);
					if (!mysqli_error($con)) $sucesso = true;
					else {
						echo "Ocorreu um erro";
						$sucesso = false;
						break;
					}
				}
			}
			else {
				while ($_POST["idResposta".$i."_$j"]  !== NULL) {
					if ($_POST["resposta".$i."_$j"] != NULL) {
						$idR = $_POST["idResposta".$i."_$j"];
						$sql = "insert into $tabela (cd_usuario, cd_enquete, cd_pergunta, cd_resposta, dt_voto) values ($idu, $idEnquete, $idP, $idR, '$data')";
						mysqli_query($con, $sql);
						if (!mysqli_error($con)) $sucesso = true;
						else {
							echo "Ocorreu um erro.";
							$sucesso = false;
							break;
						}
					}
					$j++;
				}
			}
			$i++;
		}
		if ($sucesso && !$condicao) {
			echo "<font color='green'><b>Voto efetuado com sucesso.</b></font><br>";	
		} elseif ($sucesso) {
			echo "<font color='green'><b>Voc&ecirc; alterou suas respostas com sucesso.</b></font><br>";
		}
		$this->idu = $idu;
	}
	public function valida_enquete1 () {
		global $status;
		$conditions = array();
		$conditions['enquete'] = 1;
		if (isset($_POST['usuario'])) {
			$conditions['usuario'] = 'email';
			$conditions['senha'] = 7;
		}
		$valid = $this->validateForm($_POST, $conditions);
		if (is_string($valid)) {
			$status = $valid;
			$this->write_status();
			exit();
		}
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
			if ($ide == NULL) $POST["dt_criacao"] = date("$dateformat $timeformat");
			if ($POST['code'] == NULL) $POST['code'] = $this->codeGenerator();
			if ($POST['acima_de_cem'] == NULL) $POST['acima_de_cem'] = 0;
			$POST["disponivel"] = 1;
			if ($ide != NULL) mysqli_query($this->con, "update poll_html set mudou = 1 where cd_enquete = $ide and is_poll = true");
		}
		$this->adminPage ($POST, $_FILES, $_SESSION, $this->formTabela2, array(), array());
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
			$this->adminPage ($POST, $_FILES, $_SESSION, $this->formTabela5, array(), array());
		}
	}
	public function crud_pergunta_respostas($ide) {
		global $POST;
		global $status;
		$con = $this->con;
		if ($POST['del'] == "Excluir Pergunta" && $POST['idPergunta'] !== NULL) {
			$POST['butPres'] = $_POST['butPres'];
			mysqli_query($con, "delete from voto where cd_pergunta = ".$POST['idPergunta']);
		}
		if ($POST['idPergunta'] !== NULL) {
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
		unset($this->formTabela4[0][3]);
		
		$this->adminPage ($POST, $_FILES, $_SESSION, $this->formTabela3, $this->formTabela4, array());
		if ($POST['enquete_ou_teste'] == '1') {
			if (isset($POST['cd_resposta'])) {
				//var_dump($POST['idPergunta']);
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
	public function editing_messages($ide) {
		global $service_data;
		echo "<br><br><br>";
	?>
		<p><a href="criar_enquete.php?ide=<?php echo $ide;?>&np=true">Voltar e editar ou criar nova pergunta para a enquete.</a></p>
		<br>
		<p><a href="enquete.php?ide=<?php echo $ide;?>">Ir para a enquete.</a></p>
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

?>
