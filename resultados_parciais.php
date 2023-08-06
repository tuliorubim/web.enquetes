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
 	<form name="form" method="post">
    <?php
	require_once "funcoes/funcoesDesign.php";
	class Result extends DesignFunctions {
		public $idu;
		public $con;
		private $idEnquete;
		public function __construct($idu, $con, $ide=0) {
			$this->idu = $idu;
			$this->con = $con;
			$this->idEnquete = $ide;
		}	
		public function processa_voto_remoto($idEnquete) {
			$idu = $this->idu;
			$con = $this->con;
			$idSession = $this->idSession;
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
		public function create_result_header ($cd_servico, $cd_usuario, $hide_results) {
			global $service_data;
			$idEnquete = $_GET['ide'];
			$args = $this->select("select e.enquete, e.usar_logo, cl.logo from enquete e inner join cliente cl on e.cd_usuario = cl.idCliente where idEnquete = $idEnquete");
			$idu = $this->idu;
			if ($cd_servico > 0 && $idu === $cd_usuario) { 
		?>
			<p><a id="pdf" href="pdf_result.php?ide=<?php echo $idEnquete;?>">Baixar PDF deste resultado</a></p>
		<?php 
			}
		?>
			<h2>Resultados parciais</h2>
			<h4>da enquete: <?php echo $args[0]['enquete']; ?></h4>
		<?php
			if ($cd_servico > 0 && $args[0]['usar_logo'] && !$hide_results) {
				$this->exibir_imagem($args[0]['logo'], 700);
			}
			if (empty($service_data) && $cd_usuario == $idu) {
		?>
			<p><a href="bonus_mensais.php" target="_blank">Experimente assinatura gr&aacute;tis</a></p>
		<?php
			}
		}
		public function show_result ($idPergunta, $cd_resposta_certa) {
			$cdu = $this->select("select cd_usuario from enquete where idEnquete = $this->idEnquete");
			$idu = $this->idu;
			if ($cd_resposta_certa == 0 || $idu == $cdu[0][0]) {
				return true;
			} else {
				$args = $this->select("select cd_resposta from voto where cd_usuario = $idu and cd_pergunta = $idPergunta");
				return !empty($args[0][0]);
			}
		}
		public function create_results ($cd_servico) {
			$idEnquete = $this->idEnquete;
			$html = $this->html;
			$idu = (!isset($_SESSION['user2'])) ? $this->idu : $_SESSION['user2'];
			$sql = "select p.idPergunta, p.pergunta, p.multipla_resposta, p.valor, p.cd_resposta_certa, r.idResposta, r.resposta, count(v.dt_voto) as votos from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta left join voto v on r.idResposta = v.cd_resposta where p.cd_enquete = $idEnquete group by r.idResposta order by p.idPergunta, count(v.dt_voto) desc, r.idResposta";
			$args = $this->select($sql);
			$args1 = $this->select("select count(idPergunta) as num_quest from pergunta where cd_enquete = $idEnquete");
			$idP = 0;
			$j = -1;
			$show_result = true;
			$cd_resposta_certa = 0;
			for ($i = 0; array_key_exists($i, $args); $i++) {
				if ($idP != $args[$i]["idPergunta"]) {
					$cd_resposta_certa = $args[$i]['cd_resposta_certa'];
					$idP = $args[$i]["idPergunta"];
					$show_result = $this->show_result($idP, $cd_resposta_certa); 
					if ($show_result) $html .= "<div class='pergunta'>".$args[$i]["pergunta"]."</div>";
					$args2 = $this->select("select count(dt_voto) as votos_pergunta from voto where cd_pergunta = $idP");
					$minha_resposta = $this->select("select cd_resposta from voto where cd_pergunta = $idP and cd_usuario = $idu");
					$j++;
				}
				if ($show_result) {
					$input = '';
					$idR = $args[$i]["idResposta"];
					if ($args1[0]['num_quest'] > 1 || $args[$i]["multipla_resposta"] == 1) {
						$input = " <input ";
						if ($cd_servico == 0) {
							$input .= "type='radio' name='resposta' ";
						} else $input .= "type='checkbox' name='resposta$i' ";
						$input .= "class='resposta' value=$idR title='Clique aqui e saiba como as pessoas que votaram nesta resposta votaram nas respostas das outras perguntas.'>";
					}
					$porcentagem = 0;
					$votos_resposta = $args[$i]['votos'];
					$votos_pergunta = $args2[0]['votos_pergunta'];
					if ($votos_pergunta > 0) $porcentagem = round(100*$votos_resposta/$votos_pergunta, 1);
					$html .= "<p id='resposta_$i'>".$args[$i]["resposta"]." $input&nbsp;&nbsp;&nbsp;&nbsp;";
					if ($cd_resposta_certa > 0) {
						if (isset($minha_resposta[0]['cd_resposta'])) {
							$fundo_verde = "<script language='javascript'>\$(function () {\$('#resposta_$i').css('background-color', 'green');});</script>";
							$fundo_vermelho = "<script language='javascript'>\$(function () {\$('#resposta_$i').css('background-color', 'red');});</script>";
							if ($args[$i]['idResposta'] == $cd_resposta_certa && $args[$i]['idResposta'] == $minha_resposta[0]['cd_resposta']) {
								$html .= "Voc&ecirc; acertou ";
								echo $fundo_verde;
							} elseif ($args[$i]['idResposta'] == $cd_resposta_certa) {
								$html .= "Resposta certa ";
								echo $fundo_verde;
							} elseif ($args[$i]['idResposta'] == $minha_resposta[0]['cd_resposta']) {
								$html .= "Sua resposta ";
								echo $fundo_vermelho;
							}
						}
						$args4 = $this->select("select p.valor, count(v.dt_voto) as total, (select count(dt_voto) from voto where cd_pergunta = p.idPergunta and cd_resposta = $cd_resposta_certa) as certas from pergunta p inner join voto v on p.idPergunta = v.cd_pergunta where p.idPergunta = $idP");
						$media_pontos = $args4[0]['valor']*$args4[0]['certas']/$args4[0]['total'];
						$porcentagem_acertos = 100*$args4[0]['certas']/$args4[0]['total'];
						
					}
					$html .= "<span id='votos$i' style='font-weight:600;'>$votos_resposta votos, $porcentagem %</span></p>";
					if (!array_key_exists($i+1, $args) || $idP != $args[$i+1]["idPergunta"]) {
						$html .= "<p><span class='resposta' id='vp$j' style='font-weight:600;'>Total de votos: $votos_pergunta";
						if ($cd_resposta_certa > 0) $html .= ", Pontua&ccedil;&atilde;o m&eacute;dia das pessoas: ".round($media_pontos, 2).", Porcentagem de acertos: ".round($porcentagem_acertos, 1)." %</span></p>";
					}
				}
			}
			$args3 = $this->select("select count(dt_voto) as votos_enquete from voto where cd_enquete = $idEnquete");
			$html .= "<p><span class='resposta' id='ve' style='font-weight:600;'>Total de respostas: ".$args3[0]['votos_enquete']."</span></p>";
			$this->html = $html;
		}
		public function create_results2 () {
			$idEnquete = $this->idEnquete;
			$html = $this->html;
			$idu = (!isset($_SESSION['user2'])) ? $this->idu : $_SESSION['user2'];
			$sql = "select p.idPergunta, p.pergunta, p.valor, p.cd_resposta_certa, r.idResposta, r.resposta from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta where p.cd_enquete = $idEnquete and (p.cd_resposta_certa > 0 and p.idPergunta = (select v.cd_pergunta from voto v where v.cd_pergunta = p.idPergunta and v.cd_usuario = $idu))";
			$args = $this->select($sql);
			if (count($args) === 0) {
				$status = "Agradecemos o seu voto. Ele foi corretamente computado.";
				die ("<script language='javascript'>$('#status').html('<font color=green>$status</font>');</script>");
			}
			$idP = 0;
			$j = -1;
			$cd_resposta_certa = 0;
			for ($i = 0; $args[$i][0] !== NULL; $i++) {
				if ($idP != $args[$i]["idPergunta"]) {
					$cd_resposta_certa = $args[$i]['cd_resposta_certa'];
					$idP = $args[$i]["idPergunta"];
					$html .= "<div class='pergunta'>".$args[$i]["pergunta"]."</div>";
					$minha_resposta = $this->select("select cd_resposta from voto where cd_pergunta = $idP and cd_usuario = $idu");
					$j++;
				}
				$html .= "<p id='resposta_$i'>".$args[$i]["resposta"]."&nbsp;&nbsp;&nbsp;";
				if (isset($minha_resposta[0]['cd_resposta'])) {
					$fundo_verde = "<script language='javascript'>\$(function () {\$('#resposta_$i').css('background-color', 'green');});</script>";
					$fundo_vermelho = "<script language='javascript'>\$(function () {\$('#resposta_$i').css('background-color', 'red');});</script>";
					if ($args[$i]['idResposta'] == $cd_resposta_certa && $args[$i]['idResposta'] == $minha_resposta[0]['cd_resposta']) {
						$html .= "Voc&ecirc; acertou ";
						echo $fundo_verde;
					} elseif ($args[$i]['idResposta'] == $cd_resposta_certa) {
						$html .= "Resposta certa ";
						echo $fundo_verde;
					} elseif ($args[$i]['idResposta'] == $minha_resposta[0]['cd_resposta']) {
						$html .= "Sua resposta ";
						echo $fundo_vermelho;
					}
				}
				$html .= "</p>";
			}
			$this->html = $html;
		}
		public function print_comments() {
			$idEnquete = $this->idEnquete;
			$sql = "select c.nome, c.usuario, c.idCliente, co.* from cliente c inner join comentario co on c.idCliente = co.cd_cliente where cd_enquete = $idEnquete order by idComentario desc";
			$args = $this->select($sql);
			$r = '';
			for ($i = 0; array_key_exists($i, $args); $i++) {
				if (!empty($args[$i][0])) {
					if (strpos($args[$i][0], ' ') !== FALSE)
						$r .= substr($args[$i][0], 0, strpos($args[$i][0], ' ')) ;
					else $r .= $args[$i][0];
				} elseif (!empty($args[$i][1])) {
					$r .= substr($args[$i][1], 0, strpos($args[$i][1], '@'));
				} else $r .= "An&ocirc;nimo".$args[$i][2];	
				$r .= " em ".$this->std_datetime_create($args[$i][7]).': ';
				$r .= $args[$i][6]."<br><br>";
			}
			echo $r;
		}
	}
	//var_dump($_POST);
	$idEnquete = (array_key_exists('ide', $_GET)) ? $_GET['ide'] : $_POST['ide'];
	$esconder = false;
	$we->select("select e.cd_usuario, e.disponivel, e.hide_results, cl.cd_servico from enquete e inner join cliente cl on e.cd_usuario = cl.idCliente where idEnquete = $idEnquete", array("cd_usuario", "disponivel", "hide_results", "cd_servico"));
	$we->select("select code from enquete where idEnquete = $idEnquete", array("code"), true);
	if (true) {
		if (array_key_exists('from', $_GET) && $_GET['from'] == 'cross') {
			$we->select("select max(cd_usuario) from voto where cd_enquete = $idEnquete", array('id'));
			if ($we->idu != $id) {
				$we->idu = $id;
				$_SESSION[$we->idSession] = $id;
			}
		}
		$result = new Result($we->idu, $we->con, $idEnquete);
		if (!$disponivel) {
			$status = "Enquete encerrada.";
			echo "<script language='javascript'>$('#status').html('<font color=red>$status</font>');</script>";
		}
		if (array_key_exists('pollcode', $_POST) && $_POST['pollcode'] === $code) {
			$result->processa_voto_remoto($idEnquete);
		}
		
		if (!$hide_results || $we->idu === $cd_usuario) {
			$result->create_result_header ($cd_servico, $cd_usuario, $hide_results);
		?>	
			<a href="enquete.php?ide=<?php echo $idEnquete;?>">Voltar</a>
			<p><div class="fb-like" data-href="https://www.facebook.com/WebEnquetesEPesquisas/" data-width="100" data-layout="standard" data-action="like" data-size="small" style='margin: 3px;' data-show-faces="true" data-share="false"></div></p>
		<?php
			$site = (!array_key_exists('site', $_GET)) ? NULL : $_GET['site'];
			if ($site === 'true' && $cd_usuario == $we->idu) {
				echo "<p><font color='red'>Caro $nome, se voc&ecirc; quer que a enquete no seu site mostre uma pergunta de cada vez, ocupando menos espa&ccedil;o no seu site, e quer que a marca da Web Enquetes seja removida, fa&ccedil;a <a href='premium.php'>aqui</a> sua assinatura e adquira tamb&eacute;m outras vantagens al&eacute;m dessas, como assinante. Ap&oacute;s adquirir a assinatura, fa&ccedil;a o download do novo HTML da sua enquete e cole-o no lugar do HTML antigo da mesma em seu site, para que voc&ecirc; obtenha os benef&iacute;cios acima citados. Somente voc&ecirc; pode ver esta mensagem.</font></p>";
			}
			$result->create_results($cd_servico);
			echo $result->html;
			$variaveis = array("idComentario", "cd_cliente", "cd_enquete", "comentario", "dt_comentario");
			$tipos = array("integer", "integer", "integer", "varchar", "dateTime");
			$maxlengths = array("", "", "", "2048", "");
			$tabela = "comentario";
			$formTabela = array($variaveis, $tipos, NULL, NULL, NULL, $tabela, $maxlengths);
			$result->createTable($formTabela, 1);
		?>
		<div id='novo_comentario'>
		<br><br>
		<b>Poste um coment&aacute;rio:</b><br />
		<form name='novo_comentario' method="post">
			<textarea name="comentario" id="comentario" rows="3" cols="40"></textarea>
			<div style="margin-top: 10px;"><input type="button" class="btn btn-primary estilo-modal" name="postar" value="Postar" /></div>
		</form>	
		</div>
		<div id='comentarios'>
	<?php
			$result->print_comments();
	?>
		</div>
		<script language="javascript">
		$(function () {
			comments = $("#comentarios").html();
			offset = 0;
			url_pos = comments.indexOf("https://", offset);
			var regExp = new RegExp('^[,\\.;]$');
			while (url_pos != -1) {
				pos_br = comments.indexOf('<br>', url_pos);
				pos_space = comments.indexOf(' ', url_pos);
				fim = (pos_br < pos_space || pos_space == -1) ? pos_br : pos_space;
				url = comments.substring(url_pos, fim);
				//alert(url_pos+' '+fim+' '+url);
				url_last_char = ''+url.charAt(url.length-1);
				if (url_last_char.search(regExp) != -1) {
					url = url.substring(0, url.length-1);
				}
				url_tag = "<a href='"+url+"' target='_blank'>";
				if (url.indexOf("webenquetes.com.br/enquete.php?ide=") != -1) {
					url_tag = "<span style='font-size: 18px; font-weight: 800; color: #006600;'>COMENT&Aacute;RIO EM DESTAQUE: </span>"+url_tag;
				}
				comments = Insere(comments, url_tag, url_pos);
				offset = url_pos+url_tag.length+5;
				comments = Insere(comments, "</a>", offset-5+url.length);
				url_pos = comments.indexOf("https://", offset);
			}
			$("#comentarios").html(comments);
		});
		</script>
	<?php		
		} else {
			$result->create_results2();
			if (!empty($result->html)) {
	?>	
				<h2>Resultado do teste</h2>
				<a href="enquete.php?ide=<?php echo $idEnquete;?>">Voltar</a>
	<?php	
				echo $result->html;		
			}	
		}
	} else {
		$status = "Os resultados parciais desta enquete n&atilde;o est&atilde;o dispon&iacute;veis no momento.";
		echo "<script language='javascript'>$('#status').html('<font color=red>$status</font>');</script>";
	}
	?>
	<script language="javascript">
	ide = <?php echo $idEnquete;?>;
	idu = <?php echo $we->idu;?>;
	cds = <?php echo $cd_servico;?>;
	data_lim = '<?php echo '';//$data_lim;?>';
	var idr = null;
	$(document).ready( function () {
		$(".resposta").click(function () {
			idr = null;
			if (cds === 0) {
				idr = document.form.resposta.value;
				if ($("#pdf").html() != null) {
					$("#pdf").attr("href", "pdf_result.php?ide="+ide+"&idr0="+idr);
				}
			} else {
				idr = [];
				j = 0;
				res = document.form.resposta0;
				pdf_page = "pdf_result.php?ide="+ide;
				for (i = 0; res; i++) {
					if ($("input[name='resposta"+i+"']").prop("checked") == true) {
						idr[j] = $("input[name='resposta"+i+"']").val();
						pdf_page += "&idr"+j+"="+idr[j];
						j++;
					}
					eval("res = document.form.resposta"+(i+1));
				}
				if ($("#pdf").html() != null) {
					$("#pdf").attr("href", pdf_page);
				}
			}	
			$.ajax({
				url: "result.php",
				type: "POST",
				dataType: "json",
				data: {
					ide: ide,
					idr: idr,
					cds: cds, 
					data_lim: data_lim
				},
				success: function (result) {
					$(".resposta").hide();
					$("#ve").html(result['ve']);
					for (i = 0; result['vp'+i] != null; i++) {
						$("#vp"+i).html(result['vp'+i]);
					}
					for (i = 0; result['votos'+i] != null; i++) {
						$("#votos"+i).html(result['votos'+i]);
					}
					$(".resposta").fadeIn(1000);
				},
				error: function (xhr, s, e) {
					alert(xhr.responseText);
				}
			});
		});
		$("input[name='postar']").click(function () {
			comment = $("#comentario").val();
			/*offset = 0;
			comment_aux = comment;
			ht_pos1 = comment_aux.indexOf("https://", offset);
			ht_pos2 = comment_aux.indexOf("http://", offset);
			while (ht_pos1 !== -1 || ht_pos2 !== -1) {
				ht_pos = (ht_pos1
				lnk = comment_aux.substr();
			}*/
			if (/*comment.indexOf("'") === -1 && */comment.length > 0 && idu > 0) {
				$.ajax({
					url: "comentarios.php",
					type: "POST",
					dataType: "json",
					data: {
						ide: ide,
						idu: idu,
						comment: comment
					},
					success: function (result) {
						$("#comentarios").prepend(result["comment"]);
						$("#comentario").val('');
					},
					error: function (xhr, s, e) {
						alert(xhr.responseText);
					}
				});
			} else if (comment.indexOf("'") !== -1) {
				alert("Aspas simples (') n\u00e3o s\u00e3o permitidas.");
			}
		});
	});
	/*$("#pdf").click(function () {
		$.ajax({
			url: "pdf_result.php",
			type: "POST",
			dataType: "json",
			data: {
				ide: ide,
				idr: idr
			},
			success: function (result) {
			
			}, 
			error: function (xhr, s, e) {
				alert(xhr.responseText);
			}
		});
	});*/
	</script>
	
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