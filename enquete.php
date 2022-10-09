<?php
include "bd.php";
//contagem2 ('cont_enquete', true, $_GET['ide']); 
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
	<div id='status'></div>
	<form name="form" method="post">
    <?php
	$ide = $_GET['ide'];
	//$multipla_resposta = 0;
	if (!empty($ide)) {
		$we->select("select disponivel, cd_usuario from enquete where idEnquete = $ide", array('disponivel', 'cd_usuario'));
	}
	if ($disponivel || $cd_usuario == $we->idu) {
		class Enquete extends DesignFunctions {
			public $idu;
			private $idEnquete;
			public function __construct($idu, $ide, $con) {
				$this->idu = $idu;
				$this->idEnquete = $ide;
				$this->con = $con;
			}
			public function create_poll_header($disponivel, $cd_usuario) {
				global $status;
				global $service_data;
				$ide = $this->idEnquete;
				$idu = $this->idu;
				if (!$disponivel) {
					$status = "Esta enquete n&atilde;o est&aacute; dispon&iacute;vel. S&oacute; voc&ecirc; pode v&ecirc;-la.";
					echo "<script language='javascript'>$('#status').html('<font color=red>$status</font>');</script>";
				}
				$args = $this->select("select c.idCategoria, c.categoria, e.enquete, e.introducao, e.dt_criacao, e.hide_results from categoria c inner join enquete e on c.idCategoria = e.cd_categoria where idEnquete = $ide");
				echo "<h4>".strip_tags($args[0]['enquete'])."</h4>";
				$args2 = $this->select("select c.cd_servico, c.logo, e.usar_logo from cliente c inner join enquete e on c.idCliente = e.cd_usuario where e.idEnquete = $ide");
				if ($args2[0]['cd_servico'] > 0 && $args2[0]['usar_logo']) {
					$this->exibir_imagem($args2[0]['logo'], 700);
				}
				echo "<p>Enquete sobre ".$args[0]['categoria']." criada em ".$this->std_date_create($args[0]['dt_criacao'])."</p>";
				if (empty($service_data) && $cd_usuario == $idu) {
			?>
				<p><a href="bonus_mensais.php" target="_blank">Experimente assinatura gr&aacute;tis</a></p>
			<?php
				}
				if (!$args[0]['hide_results'] || $cd_usuario == $idu) {
			?>
				<p><a href='resultados_parciais.php?ide=<?php echo $ide; ?>' id="result">Ver resultados parciais. </a></p>
			<?php
				}
				if (!empty($args[0]['introducao'])) {
					$content = str_replace("\n", '<br>', $args[0]['introducao']);
					$content = str_replace("<script", "", $content);
					$content = str_replace("<?", "", $content);
					echo "<p>".$content."</p>";
				}
			}
			public function show_question ($idPergunta, $cd_resposta_certa) {
				if ($cd_resposta_certa == 0) {
					return true;
				} else {
					$idu = $this->idu;
					$args = $this->select("select cd_resposta from voto where cd_usuario = $idu and cd_pergunta = $idPergunta");
					return empty($args[0][0]);
				}
			}
			public function create_poll ($cross=FALSE, $cd_servico=1, $cdu=NULL) {
				$idu = ($_SESSION['user'] !== NULL) ? $_SESSION['user'] : $cdu;
				$ide = $this->idEnquete;
				$dateformat = $this->dateformat;
				$timeformat = $this->timeformat;
				$html = $this->html;
				if (!empty($ide)) {
					$args1 = $this->select("select disponivel from enquete where idEnquete = $ide");
				}
				if ($args1[0]['disponivel']) {
					$args2 = $this->select("select cd_usuario, enquete, hide_results from enquete where idEnquete = $ide");
					$args3 = $this->select("select code from enquete where idEnquete = $ide", array(), true);
					
					$variaveis = array("idPergunta", "cd_enquete", "pergunta", "multipla_resposta", "valor", "cd_resposta_certa");
					$tipos = array("integer", "integer", "varchar", "boolean", "decimal", "integer");
					$labels = array();
					$inputs = array("hidden", "hidden", "html", "hidden", "hidden", "hidden");
					$maxlengths = array("", "", "1024", "", "4,2", "");
					$properties = NULL;
					$tabela = "pergunta";
					$enderecos = array();
					
					$formTabela1 = array($variaveis, $tipos, $labels, $inputs, $enderecos, $tabela, $maxlengths, $properties);
					
					$variaveis2 = array("idResposta", "cd_pergunta", "letra", "resposta");
					$tipos2 = array("integer", "integer", "char", "varchar");
					$labels2 = array();
					$inputs2 = array("hidden", "hidden", "hidden", "radio");
					$maxlengths2 = array("", "", "1", "1024");
					$tabela2 = "resposta";
					$enderecos2 = array();
					
					$formTabela2 = array($variaveis2, $tipos2, $labels2, $inputs2, $enderecos2, $tabela2, $maxlengths2, array(), 0, "readonly");
					
					$inds = array(0, 0);
					$select = array('', 'form', 'no_print');
					$args = $this->select("select idPergunta, multipla_resposta, cd_resposta_certa, valor from pergunta where cd_enquete = $ide order by idPergunta");
					$select[5] = true;
					$idp = 0;
					$h = '';
					for ($i = 0; $args[$i][0]; $i++) {
						if ($idp !== $args[$i][0]) {
							$args4 = $this->select("SELECT count(idResposta) as cont from resposta where cd_Pergunta = ".$args[$i][0]);
							$cont = $args4[0]['cont'];
							$formTabela2[8] = $cont-1;
						}
						$idp = $args[$i][0];
						$crc = $args[$i]["cd_resposta_certa"];
						if ($this->show_question ($idp, $crc)) {
							$select[0] = "select p.*, r.idResposta, r.cd_pergunta, concat(r.letra, ')'), r.resposta from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta where idPergunta = $idp order by r.idResposta";
							$formTabela1[8] =  $i+1;
							$crc = $args[$i]["cd_resposta_certa"];
							if ($crc > 0) {
								$formTabela1[2][2] = "Teste: ";
								$valor = $args[$i]["valor"];
								if ($valor > 0) {
									$formTabela1[2][4] = "Valor do teste: ";
									$formTabela1[3][4] = "html";
									$formTabela2[3][2] = "html";
								} else {
									$formTabela1[2][4] = "";
									$formTabela1[3][4] = "hidden";
									$formTabela2[3][2] = "hidden";
								}
							} else {
								$formTabela1[2][2] = "";
								$formTabela1[2][4] = "";
								$formTabela1[3][4] = "hidden";
								$formTabela2[3][2] = "hidden";
							}
							if ($args[$i][1]) {
								$formTabela2[3][3] = "checkbox";
								for ($j = 0; $j < $cont; $j++) {
									$formTabela2[7][$j] = array('', '', '', "onclick='this.value=this.checked;'");		
								}
							} else {
								$formTabela2[3][3] = "radio";
								$formTabela2[7] = array();
							}
							if ($args[$i][2] > 0) {
								$h .= "<style type='text/css'>";
								$h .= "#l_valor".($i+1)." {float: left; margin-right: 5px}\n";
								for ($j = 0; $j < $cont; $j++) {
									$h .= "#d_letra".($i+1)."_$j {float: left; margin-right: 5px}\n";
								}
								$h .= "</style>";
							}
							/*if ($i === 1)
								$select[4] = "no_print";*/
							$this->formGeral ($_SESSION, $formTabela1, $formTabela2, NULL, $select, true);
							$h .= $this->html;
						}
					}
					if (!$cross || ($args2[0]['cd_usuario'] !== NULL && $args2[0]['cd_usuario'] == $idu)) {
						$url = '';
						$dois = '';
						$show_results = "window.location.href = 'resultados_parciais.php?ide='+cd_enquete;";
						$jquery = '';
						$form_open = '';
						$form_close = '';
						if ($cross) {
							$form_open = "<div id='question_num' style='font-size:18px; font-weight:800'></div>\n<form name='enquete' method='post'>\n<input type='hidden' name='ide' id='ide' value='$ide'>\n";
							$form_close = "<div id='botao_votar'><button type='button' id='responder'>RESPONDER</button></div></form>\n";
							$url = 'https://www.webenquetes.com.br/';
							$dois = '2';
							$show_results = "window.open(url+'resultados_parciais2.php?ide='+cd_enquete, 'Resultados Parciais', 'width=800 height=400');";
							$jquery = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>\n";
						}
						if ($cd_servico == 0) {
							$h = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>\n<form name='enquete' method='post' action='https://www.webenquetes.com.br/resultados_parciais.php?ide=$ide'>\n<input type='hidden' name='pollcode' value='".$args3[0]['code']."'>\n<a href='https://www.webenquetes.com.br/'><img src='https://www.webenquetes.com.br/img/logo-web-enquetes.png' width='120'></a>\n<p><a href='https://www.webenquetes.com.br/resultados_parciais.php?ide=$ide&site=true' id='result'>Ver resultados parciais. </a></p>\n".$h."<div id='botao_votar'><input type='submit' name='votar' id='responder' value='Votar' /></div></form>";
						} else {
							$htm = $h;
							$h = $jquery;
							if (!$args2[0]['hide_results'] && $cross) {
								$h .= "<p><a href='".$url."resultados_parciais.php?ide=$ide' id='result' target='_blank'>Ver resultados parciais. </a></p>\n";
							}
							$h .= $form_open.$htm.$form_close;
							$h .= "<script language='javascript'>
			var mr = []; ";
							for ($j = 0; $j < $i; $j++) {
								$h .= "mr[$j] = ".$args[$j][1]."; ";
							}
							$h .= "var num_questions = $i;</script>";
							if ($cross) {
								$h .= "<script language='javascript'>
								var cd_enquete = $ide;
			function selectQuestion (n) {
				if (num_questions > 1)
					\$('#question_num').html('Quest&atilde;o '+n+':');
				for (i = 1; \$('#data'+i).html() != null; i++) {
					if (i !== n) {
						\$('#data'+i).hide();
					} else \$('#data'+i).fadeIn(1000);
				}	
			}
			function setCookie(cname, cvalue, exyears) {
			  var d = new Date();
			  d.setTime(d.getTime() + (exyears * 365 * 24 * 60 * 60 * 1000));
			  var expires = 'expires='+d.toUTCString();
			  document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
			}
			
			function getCookie(cname) {
			  var name = cname + '=';
			  var ca = document.cookie.split(';');
			  for(var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') {
				  c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
				  return c.substring(name.length, c.length);
				}
			  }
			  return '';
			}
			\$(document).ready(function () {
				var q = 1;
				selectQuestion(q);
				for (i = 1; \$('#d_pergunta'+i).html() != null; i++) {
					\$('#d_pergunta'+i).css({'font-size' : '18px', 'font-weight' : '800'});
					for (j = 0; \$('#d_resposta'+i+'_'+j).html() != null; j++) {
						\$('#d_resposta'+i+'_'+j).css('font-size', '18px');
					}
				}
				\$('#responder').click(function () {
					if (cd_enquete !== 0) {
						cd_resposta = [];
						i = 0;
						if (mr[q-1] == 0) {
							cd_resposta[0] = eval('document.enquete.resposta'+q+'_.value');
						}
						else {
							i = 0;
							for (j = 0; \$('#idResposta'+q+'_'+j).val() != null; j++) {
								if (\$('#resposta'+q+'_'+j).prop('checked')) {
									cd_resposta[i] = \$('#idResposta'+q+'_'+j).val();
									i++;
								}
							}
						}
						url = '$url';
						\$.ajax({
							url: url+'voto$dois.php',
							type: 'GET',
							dataType: 'jsonp',
							data: {
								cd_usuario: getCookie('user'),
								cd_enquete: cd_enquete,
								cd_pergunta: \$('#idPergunta'+q).val(),
								cd_resposta: cd_resposta
							},
							success: function (result) {
								\$('#status').html(result['status']);
								if (result['user'] != null) {
									setCookie('user', result['user'], 10);
								}
								if (result['status'] == '') {
									q++;
									selectQuestion(q);
								} else if (result['status'].indexOf('sucesso') != -1) {
									alert(result['status']);
									$show_results
								} 
							},
							error: function (xhr, s, e) {
								alert(xhr.responseText);
							}
						});
					}
				});
			});
			</script>";
							}
						}	
					}
				} 
				$html = $h;
				$this->html = $html;
			}
		}
		$poll_html = new Create_HTML($idu, $ide, $con);
		$poll_html->create_poll_header($disponivel, $cd_usuario);
	?>
	<div class="fb-like" data-href="https://www.facebook.com/WebEnquetesEPesquisas/" data-width="100" data-layout="standard" data-action="like" data-size="small" style='margin: 3px;' data-show-faces="true" data-share="false"></div>
	<div id="question_num" style="font-size:18px; font-weight:800"></div><button type="button" disabled="disabled" class="glyphicon glyphicon-chevron-left" id="previous"></button>
	<button type="button" class="glyphicon glyphicon-chevron-right" id="next"></button>
		<?php
		/*$poll = $poll_html->select_html_from_db(true);
		if (!$poll[0] && $poll[1]) {
			echo $poll[1];
		} else {*/
			$poll_html->create_poll();
			//$poll_html->save_html_to_db($poll_html->html, true);
			echo $poll_html->html;
		//}	
		?>	
	<br>
	<button type="button" class="btn btn-primary estilo-modal" id="responder">RESPONDER</button>
	<script language="javascript">
	if (num_questions == 1) {
		$("#previous").css("display", "none");
		$("#next").css("display", "none");
	}
	</script>
	<?php
	} else {
		$variaveis = array("cd_usuario", "cd_enquete", "cd_pergunta", "cd_resposta", "dt_voto");
		$tipos = array("integer", "integer", "integer", "integer", "datetime");
		$tabela = "voto";
		$formTabela = array($variaveis, $tipos, array(), array(), array(), $tabela);
		$we->createTable($formTabela, 4);
		echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=resultados_parciais.php?ide=$ide'>";
	}
	?>
	
    <!-- AQUI É O LIMITE PARA A COLUNA ESQUERDA, O CONTEÚDO DEVE ESTAR O ÍNICIO E AQUI O FIM -->
	</form>
    </div>
    
	<?php include "sidebar.php"; ?>    
    
    </div>
	<div class="row">
    <div class="col-md-12">
    <div class="bt-all">
    <a href="enquetes.php" class="btn btn-lg btn-success all-enq-home">TODAS AS ENQUETES</a>
	</div>
    </div>
    </div>
</div>
<script language="javascript">
var cd_enquete = <?php echo (!empty($ide)) ? $ide : 0; ?>;

function selectQuestion (n) {
	if (num_questions > 1)
		$("#question_num").html("Quest&atilde;o "+n+":");
	for (i = 1; $("#data"+i).html() != null; i++) {
		if (i !== n) {
			$("#data"+i).hide();
		} else $("#data"+i).fadeIn(1000);
	}	
	if (n == 1) $("#previous").prop("disabled", true);
	if (n == 2) $("#previous").prop("disabled", false);
}
$(document).ready(function () {
	var q = 1;
	selectQuestion(q);
	for (i = 1; $('#d_pergunta'+i).html() != null; i++) {
		$('#d_pergunta'+i).css({"font-size" : "18px", "font-weight" : "800"});
		for (j = 0; $('#d_resposta'+i+'_'+j).html() != null; j++) {
			$('#d_resposta'+i+'_'+j).css("font-size", "18px");
		}
	}
	$("#previous").click(function () {
		q--;
		selectQuestion(q);
		//if (q == num_questions-1) $("#next").prop("disabled", false);
	});
	$("#next").click(function () {
		if (q == num_questions) {
			q--; 
			if (confirm("Deseja terminar a enquete e ver os resultados parciais?")) {
				document.location.href = "resultados_parciais.php?ide="+cd_enquete;
			}  
		} 
		q++;
		selectQuestion(q);
	});
	$("#responder").click(function () {
		if (cd_enquete !== 0) {
			cd_resposta = [];
			i = 0;
			if (mr[q-1] == 0) {
				cd_resposta[0] = eval("document.form.resposta"+q+"_.value");
			}
			else {
				i = 0;
				for (j = 0; $('#idResposta'+q+'_'+j).val() != null; j++) {
					if ($('#resposta'+q+'_'+j).prop('checked')) {
						cd_resposta[i] = $('#idResposta'+q+'_'+j).val();
						i++;
					}
				}
			}
			$.ajax({
				url: 'voto.php',
				type: 'GET',
				dataType: 'jsonp',
				data: {
					cd_enquete: cd_enquete,
					cd_pergunta: $("#idPergunta"+q).val(),
					cd_resposta: cd_resposta
				},
				success: function (result) {
					$("#status").html(result['status']);
					if (result['status'] == '') {
						q++;
						selectQuestion(q);
					} else if (result['status'].indexOf("sucesso") != -1) {
						alert(result['status']);
						window.location.href = "resultados_parciais.php?ide="+cd_enquete;
					} 
				},
				error: function (xhr, s, e) {
					alert(xhr.responseText);
				}
			});
		}
	});
});
</script>

<?php include "categorias.php"; ?>

<!-- bkg-footer -->
<div class="clearfix">
<?php include "footer.php"; ?>
</div>

</body>
</html>