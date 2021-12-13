<?php
require_once "funcoes/funcoesDesign.php";
require_once "dados_webenquetes.php";
class Create_HTML extends DesignFunctions {
	use Dados_webenquetes;
	public $idu;
	private $idEnquete;
	private $idConteudo;
	private $idc;
	private $extension;
	public $select;
	
	public function __construct($ide=0, $idContent=0, $idc=NULL, $ext='html') {
		$this->idEnquete = $ide;
		$this->idConteudo = $idContent;
		$this->idc = $idc;
		$this->extension = $ext;
	}
	public function form_categorias () {
		$sql = "select * from categoria order by categoria";
		$select = array($sql, "select");
		$select[5] = true;
		
		$inds = $this->formGeral ($_SESSION, $this->formTabela1, array(), array(), $select, false, array(0, 0), true);
		$idc = $this->idc;
		$idEnquete = $this->idEnquete;
		if ($idc === NULL) $idc = 0;
		if ($idc !== 0 || $idEnquete !== NULL) {
			if ($idEnquete !== NULL) {
				$sql = "select c.idCategoria, c.categoria from categoria c left join enquete e on c.idCategoria = e.cd_categoria where e.idEnquete = $idEnquete";
			} else $sql = "select idCategoria, categoria from categoria where idCategoria = $idc";	
			$args = $this->select($sql, array(), true);
		?>
			<script language="javascript">
				$("input[name='idCategoria']").val('<?php echo $args[0]['idCategoria']; ?>');
				$("#categoria").val("<?php echo $args[0]['categoria']; ?>");
			</script>
		<?php	
		}
		$this->idc = $args[0]['idCategoria'];
		$this->select = $select;
		return $inds;
	}
	public function form_enquete($inds) {
		$select = $this->select;
		$select[1] = 'form'; 
		$idEnquete = $this->idEnquete;
		$idu = $this->idu;
		
		if ($idEnquete !== NULL) {
			$select[0] = "select * from enquete where idEnquete = $idEnquete and cd_usuario = $idu";	
		} else $select[5] = false;
			
		$inds = $this->formGeral ($_SESSION, $this->formTabela2, array(), array(), $select, false, $inds);
		
		$this->addForeignKey("enquete", "cd_categoria", "categoria", "idCategoria");
		$this->addForeignKey("enquete", "cd_usuario", "usuario", "idUsuario");
		$this->select = $select;
		return $inds;
	}
	public function upload_ad($inds, $cd_servico) {
		$select = $this->select;
		$idu = $this->idu;
		$sel = $select[5];
		if ($cd_servico > 0) {
			$select[0] = "select idCliente, logo, logoReduzida, site from cliente where idCliente = $idu";
			$select[5] = true;
			$inds = $this->formGeral ($_SESSION, $this->formTabela5, array(), array(), $select, false, $inds);
		}
		?>
		<script language="javascript">
		var cds = <?php echo (isset($cd_servico)) ? $cd_servico : 0;?>;
		if (cds > 0 && $(".lab").html() == null) $("#d_usar_logo").css("display", "none");
		</script>
		<?php
		$select[5] = $sel;
		$this->select = $select;
		return $inds;
	}
	public function form_pergunta_respostas ($inds, $cd_servico) { 
		$select = $this->select;
		$idEnquete = $this->idEnquete;
		$indEdit = 0;
		if ($_GET['np'] == "true") {
			$select[5] = false;
		} elseif (isset($_GET['idp'])) {
			$indEdit = $_GET['idp'];
		} elseif ($idEnquete > 0) {
			$rs = mysqli_query($this->con, "select idPergunta from pergunta where cd_enquete = $idEnquete order by idPergunta");
			$row = mysqli_fetch_array($rs);
			$indEdit = $row['idPergunta'];
		}
		$select[0] = "select p.*, r.* from pergunta p left join resposta r on p.idPergunta = r.cd_pergunta where p.cd_enquete = $idEnquete and p.idPergunta = $indEdit order by r.idResposta";
		$args = $this->select("select count(dt_voto) as dez from voto where cd_enquete = $idEnquete");
		$excluir = true;
		if ($args[0]['dez'] >= 10 && $cd_servico == 0) {
			$excluir = false;
			$this->formTabela4[9] = 'readonly';
		}
		
		$inds = $this->formGeral ($_SESSION, $this->formTabela3, $this->formTabela4, array(), $select, false, $inds);
		$this->select = $select;
		return array($inds, $excluir);
	}
	public function poll_for_editing () {	
		$idEnquete = $this->idEnquete;
		if ($_GET['np'] === "true" || $this->select[5] || $idEnquete !== NULL) {
			$banco = 'enquetes';
			$fields = "select p.pergunta, r.resposta from pergunta p left join resposta r on p.idPergunta = r.cd_pergunta where p.cd_enquete = $idEnquete order by p.idPergunta, r.idResposta";
			$i = 0;
			$conteudo = array();
			$rs = mysqli_query($this->con, "select idPergunta from pergunta where cd_enquete = $idEnquete order by idPergunta");
			$idc = $this->idc;
			while ($rs && $row = mysqli_fetch_array($rs)) {
				$c = $row["idPergunta"];
				$page = 'criar_enquete.php';
				if ($idc !== NULL && $idEnquete !== NULL)
					$page .= "?idc=$idc&ide=$idEnquete&idp=$c";
				elseif ($idEnquete !== NULL) 
					$page .= "?ide=$idEnquete&idp=$c";
				elseif ($idc !== NULL) 
					$page .= "?idc=$idc";
				$conteudo[$i] = "<a href='$page' >Editar</a>";
				$i++;
			}
			$incluiConteudo = array("content" => $conteudo, "beforeAfter" => "after", "level" => 0);
			echo $this->designBlocks (array("database" => $banco, "fields" => $fields, "includeContent" => $incluiConteudo));
		} 
	}
	public function form_conteudo($inds) {
		$select = $this->select;
		$select[1] = 'form'; 
		$idConteudo = $this->idConteudo;
		$idu = $this->idu;
		
		if ($idConteudo !== NULL) {
			$select[0] = "select idConteudo, cd_usuario, cd_categoria, dt_criacao, titulo, introducao, usar_logo from conteudo where idConteudo = $idConteudo and cd_usuario = $idu";	
		} else $select[5] = false;
			
		$inds = $this->formGeral ($_SESSION, $this->formTabela7, array(), array(), $select, false, $inds);
		
		$this->addForeignKey("conteudo", "cd_categoria", "categoria", "idCategoria");
		$this->addForeignKey("conteudo", "cd_usuario", "usuario", "idUsuario");
		$this->select = $select;
		return $inds;
	}
	public function form_content_type ($inds) {
		$sql = "select * from content_type order by idCType";
		$select = array($sql, "select");
		$select[5] = true;
		
		$inds = $this->formGeral ($_SESSION, $this->formTabela8, array(), array(), $select, false, array(0, 0), true, $inds);
		$idConteudo = $this->idConteudo;
		if ($idConteudo !== NULL) {
			$sql = "select ct.idCType, ct.type from content_type ct left join conteudo c on c.content_type = ct.idCType where c.idConteudo = $idConteudo";
			$args = $this->select($sql, array(), true);
		?>
			<script language="javascript">
				$("input[name='idCType']").val('<?php echo $args[0]['idCType']; ?>');
				$("#type").val("<?php echo $args[0]['type']; ?>");
			</script>
		<?php	
		}
		$this->select = $select;
		return $inds;
	}
	public function form_conteudo2 ($inds) {
		$select = $this->select;
		$select[1] = 'form';
		$idConteudo = $this->idConteudo;
		$idu = $this->idu;
		
		if ($idConteudo !== NULL) {
			$select[0] = "select idConteudo, content_type, texto, audio from conteudo where idConteudo = $idConteudo and cd_usuario = $idu";	
		} else $select[5] = false;
		
		$inds = $this->formGeral ($_SESSION, $this->formTabela9, $this->formTabela10, $this->formTabela11, $select, false, $inds);
		$arg = $this->select("select content_type from conteudo where idConteudo = $idConteudo");
		if ($select[5] && $arg[0]['content_type'] == 1) {
			$sql = "select ci.imagem, concat('<button id=\"', ci.idImagem, '\">Reutilizar no texto</button>') as button from content_image ci inner join cliente c on ci.cd_usuario = c.idCliente where c.idCliente = $idu";
			$sizes = "width='300'";
			$database = 'webenque_enquetes';
			$params = array('database'=>$database, 'fields'=>$sql, 'sizes'=>$sizes);
			echo $this->designBlocks($params);
?>
			<button type="button" disabled="disabled" class="glyphicon glyphicon-chevron-left" id="previous"></button>
			<button type="button" class="glyphicon glyphicon-chevron-right" id="next"></button>
			<script language="javascript">
			MostraUm("post_");
			j = 1;
			$(function () {
				$("#previous").click(function () {
					SlideMenos("post_");
				});
				$("#next").click(function () {
					SlideMais("post_");
				});
				$("button").click(function () {
					if (!isNaN($(this).attr("id"))) {
						post_imagem = $(this).parent().parent();
						var imagem = post_imagem.find("img").attr('src');
						if (document.form.texto.value.indexOf(imagem) != -1) {
							document.form.texto.value += "<img src='"+imagem+"'>";
						} else { 
							$.ajax({
								url: 'save_text.php',
								type: 'POST',
								dataType: 'json',
								data: {
									idConteudo: idConteudo,
									content_type: 1,
									texto: $("#texto").val(),
									idImagem: $(this).attr("id")
								},
								success: function (result) {
									if (result['status'] == 'success2') {
										document.form.texto.value += "<img src='"+imagem+"'>";
									} 
									else alert(result['status']);
								},
								error: function (xhr, s, e) {
									alert(xhr.responseText);
								}
							});
						}
					} 
				});
			});
			
			</script>
<?php			
		} else {
			echo "<script language='javascript'>$('#imagem').css('display', 'none');</script>";
		}
?>
		<script language="javascript">
		idConteudo = <?php echo isset($idConteudo) ? $idConteudo : 0;?>;
		idu = <?php echo isset($idu) ? $idu : 0;?>;
		$(function () {
			$("#d_audio").css("display", "none");
			$("#type").click(function () {
				if ($(this).val() == "Audio") {
					$("#d_audio").css("display", "");
					$("#d_texto").css("display", "none");
					$("#d_imagem1").css("display", "none");
					for (i = 1; $("#post_"+i).html() != null; i++) {
						$("#post_"+i).css('display', 'none');
					}
				} else if ($(this).val() == "Texto") {
					$("#d_audio").css("display", "none");
					$("#d_texto").css("display", "");
					$("#d_imagem1").css("display", "");
					for (i = 1; $("#post_"+i).html() != null; i++) {
						$("#post_"+i).css('display', '');
					}
				}
			});
			$("#imagem1").on('change', function () {
				if ($(this).val() != '') {
					form_data = new FormData();
					form_data.append("idConteudo", idConteudo);
					form_data.append("content_type", 1);
					form_data.append("texto", $("#texto").val());
					form_data.append("imagem", $(this).prop("files")[0], $(this).val());
					$.ajax({
						url: 'save_text.php',
						type: 'POST',
						dataType: 'json',
						processData: false,
						contentType: false,
						data: form_data,
						success: function (result) {
							if (result['status'] == 'success') {
								document.form.texto.value += "<img src='"+result['imagem']+"'>";
								$(this).val('');
								for (var i = 1; $("post_"+i).html() != null; i++) {
									$("#post_"+i).css("display", "none");
								}
								document.form.innerHTML += "<div id='post_"+i+"'><div><img src='"+result['imagem']+"' width='300'></div><div><button id='"+result['idImagem']+"'>Reutilizar no texto</button></div></div>";
							} else alert(result['status']);
						},
						error: function (xhr, s, e) {
							alert(xhr.responseText);
						}
					});
				}
			});
		});
		</script>
<?php
		$this->select = $select;
		return $inds;
	}
	public function minhas_enquetes () {
		$sql = "select * from enquete where cd_usuario = $this->idu order by idEnquete";
		$args = $this->select($sql);
		for ($i = 0; $args[$i][0] !== NULL; $i++) {
			$html = "<div class='minha_enquete'>".$args[$i]['enquete']."</div><div class='minha_enquete'><a href='criar_enquete.php?ide=".$args[$i]['idEnquete']."'>Editar e Gerenciar</a></div><div class='minha_enquete'><a href='enquete.php?ide=".$args[$i]['idEnquete']."' target='_top'>Visualizar</a></div>";
			echo $html;
		}
	}
	public function form_cliente() {
		$sql = "select idCliente, nome, empresa, site, logo, logoReduzida, data_cadastro, usuario from cliente where idCliente = $this->idu";
		$select = array($sql, "form");
		if ($this->idu !== 0) {
			$select[5] = true;
		}
		
		$this->formGeral ($_SESSION, $this->formTabela6, array(), array(), $select, true);
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
		echo "<h4>".$args[0]['enquete']."</h4>";
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
		if (!empty($args[0]['introducao']))
			echo "<p>Introdu&ccedil;&atilde;o: ".$args[0]['introducao']."</p>";
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
			
			$variaveis = array("idPergunta", "cd_enquete", "pergunta", "multipla_resposta");
			$tipos = array("integer", "integer", "varchar", "boolean");
			$labels = array();
			$inputs = array("hidden", "hidden", "html", "hidden");
			$maxlengths = array("", "", "1024", "");
			$properties = NULL;
			$tabela = "pergunta";
			$enderecos = array();
			
			$formTabela1 = array($variaveis, $tipos, $labels, $inputs, $enderecos, $tabela, $maxlengths, $properties);
			
			$variaveis2 = array("idResposta", "cd_pergunta", "resposta");
			$tipos2 = array("integer", "integer", "varchar");
			$labels2 = array();
			$inputs2 = array("hidden", "hidden", "radio");
			$maxlengths2 = array("", "", "1024");
			$tabela2 = "resposta";
			$enderecos2 = array();
			
			$formTabela2 = array($variaveis2, $tipos2, $labels2, $inputs2, $enderecos2, $tabela2, $maxlengths2, array(), 0, "readonly");
			
			$inds = array(0, 0);
			$select = array('', 'form', 'no_print');
			$args = $this->select("select idPergunta, multipla_resposta from pergunta where cd_enquete = $ide order by idPergunta");
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
				$select[0] = "select p.*, r.* from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta where idPergunta = $idp order by r.idResposta";
				$formTabela1[8] =  $i+1;
				if ($args[$i][1]) {
					$formTabela2[3][2] = "checkbox";
					for ($j = 0; $j < $cont; $j++) {
						$formTabela2[7][$j] = array('', '', "onclick='this.value=this.checked;'");		
					}
				} else {
					$formTabela2[3][2] = "radio";
					$formTabela2[7] = array();
				}
				/*if ($i === 1)
					$select[4] = "no_print";*/
				$this->formGeral ($_SESSION, $formTabela1, $formTabela2, NULL, $select, true);
				$h .= $this->html;
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
	public function create_results ($cd_servico) {
		$idEnquete = $this->idEnquete;
		$html = $this->html;
		$sql = "select p.idPergunta, p.pergunta, p.multipla_resposta, r.idResposta, r.resposta, count(v.dt_voto) as votos from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta left join voto v on r.idResposta = v.cd_resposta where p.cd_enquete = $idEnquete group by r.idResposta order by p.idPergunta, count(v.dt_voto) desc, r.idResposta";
		$args = $this->select($sql);
		$args1 = $this->select("select count(idPergunta) as num_quest from pergunta where cd_enquete = $idEnquete");
		$idP = 0;
		$j = -1;
		for ($i = 0; $args[$i][0] !== NULL; $i++) {
			if ($idP != $args[$i]["idPergunta"]) {
				$idP = $args[$i]["idPergunta"];
				$html .= "<div class='pergunta'>".$args[$i]["pergunta"]."</div>";
				$args2 = $this->select("select count(dt_voto) as votos_pergunta from voto where cd_pergunta = $idP");
				$j++;
			}
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
			$html .= "<p>".$args[$i]["resposta"]." $input&nbsp;&nbsp;&nbsp;&nbsp;<span id='votos$i' style='font-weight:600;'>$votos_resposta votos, $porcentagem %</span></p>";
			if ($idP != $args[$i+1]["idPergunta"]) {
				$html .= "<p><span class='resposta' id='vp$j' style='font-weight:600;'>Total de votos: $votos_pergunta</span></p>";
			}
		}
		$args3 = $this->select("select count(dt_voto) as votos_enquete from voto where cd_enquete = $idEnquete");
		$html .= "<p><span class='resposta' id='ve' style='font-weight:600;'>Total de respostas: ".$args3[0]['votos_enquete']."</span></p>";
		$this->html = $html;
	}
	public function print_comments() {
		$idEnquete = $this->idEnquete;
		$sql = "select c.nome, c.usuario, c.idCliente, co.* from cliente c inner join comentario co on c.idCliente = co.cd_cliente where cd_enquete = $idEnquete order by idComentario desc";
		$args = $this->select($sql);
		$r = '';
		for ($i = 0; $args[$i][3] != NULL; $i++) {
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
	public function mudou($is_poll=0) {
		$con = $this->con;
		$ide = $this->idEnquete;
		mysqli_query($con, "update poll_html set mudou = 1 where cd_enquete = $ide and is_poll = $is_poll");
	}
	public function save_html_to_file($html, $is_poll=0) {
		$ide = $this->idEnquete;
		$ext = $this->extension;
		$args = $this->select("select code from enquete where idEnquete = $ide", array(), true);
		$enq = ($is_poll) ? "enquete" : "resultados";
		$pollcode  = $args[0]['code'];
		$this->save_file ($html, "$enq$pollcode.$ext", 'w');
	}
	public function save_html_to_db($html, $is_poll=0) {
		$con = $this->con;
		$ide = $this->idEnquete;
		$rs = mysqli_query($con, "select cd_enquete from poll_html where cd_enquete = $ide and is_poll = $is_poll");
		$html = addslashes($html);
		if ($rs && mysqli_fetch_array($rs)) {
			mysqli_query($con, "update poll_html set html = '$html', mudou = 0 where cd_enquete = $ide and is_poll = $is_poll");
		} else mysqli_query($con, "insert into poll_html (cd_enquete, html, is_poll, mudou) values ($ide, '$html', $is_poll, 0)");
	}
	public function select_html_from_db($is_poll=0) {
		$con = $this->con;
		$ide = $this->idEnquete;
		$rs = mysqli_query($con, "select html, mudou from poll_html where cd_enquete = $ide and is_poll = $is_poll");
		if ($rs && $row = mysqli_fetch_array($rs)) {
			return array ($row['mudou'], $row['html']);
		} else return false;
	}
	public function my_plan_data() {
		global $service;
		global $service_data;
		$idu = $this->idu;
		$args = array();
		$args[0][0] = "Data de aquisi&ccedil;&atilde;o do plano";
		$args[0][1] = $this->std_date_create($service_data[0]['dt_aquisicao']);
		$votos_enquetes = $this->select("select e.enquete, count(v.dt_voto) as votos from enquete e inner join voto v on e.idEnquete = v.cd_enquete where e.cd_usuario = $idu group by e.idEnquete");
		for ($i = 0; $votos_enquetes[$i]["enquete"] !== NULL; $i++) {
			$args[$i+1][0] = "Quantidade de votos na enquete \"".$votos_enquetes[$i]["enquete"]."\"";
			$args[$i+1][1] = $votos_enquetes[$i]["votos"];
		}
		$num_votos = $service->get_num_votos();
		$args[$i+1][0] = "Quantidade total de votos nas suas enquetes";
		$args[$i+1][1] = $num_votos;
		$plano = '';
		$votes_free_months = $service::VOTES_FREE_MONTHS;
		if ($service_data[0]['gratis']) {
			$args[$i+2][0] = "Quantidade total de meses gratuitos";
			$args[$i+2][1] = $service_data[0]['meses_gratis'];
			$args[$i+3][0] = "Quantidade de votos restantes para se adquirir mais um m&ecirc;s gr&aacute;tis.";
			$args[$i+3][1] = $votes_free_months*$service_data[0]['meses_gratis']-$num_votos;
			$plano = "Plano gratuito: come&ccedil;a com um m&ecirc;s gratuito e voc&ecirc; ganha mais um m&ecirc;s gr&aacute;tis a cada $votes_free_months votos totais nas suas enquetes.";
		} else {
			$period = $service_data[0]['periodo_pagamento'];
			$plan_data = $service->paid_plan_data($period);
			$args[$i+2][0] = "Per&iacute;odo de pagamento";
			$args[$i+2][1] = $plan_data[3];
			$votos_restantes = 0;
			if ($plan_data[4] > 0) { //$plan_data[4] é a quantidade limite de votos para não se exibir anúncios, de acordo com o plano escolhido.
				$votos_restantes = $plan_data[4]-$num_votos;
				if ($votos_restantes > 0) {
					$args[$i+3][0] = "Quantidade de votos restantes para nossos an&uacute;ncios voltarem a ser exibidos.";
					$args[$i+3][1] = $votos_restantes;
				} else {
					$args[$i+3][0] = "Nossos an&uacute;ncios est&atilde;o sendo exibidos novamente, pois a quantidade de votos na sua enquete &eacute; ultrapassou ".$plan_data[4]." votos em ".(-$votos_restantes)." votos.";
					$args[$i+3][1] = " - ";
				}
			} else {
				$args[$i+3][0] = "Sua enquete n&atilde;o exibir&aacute; nossos an&uacute;ncios, indepentendemente da quantidade de votos que voc&ecirc; adquirir para sua enquetes.";
				$args[$i+3][1] = " - ";
			}
			$months_gone = $service->months_gone($service_data[0]['dt_aquisicao']);
			for ($j = 0; $j < $months_gone; $j += $period) {}
			$months_acq_date = strtotime($service_data[0]['dt_aquisicao'])/($service::MES*86400);
			$next_pay_date = date($this->dateformat, ($months_acq_date+$j)*$service::MES*86400);
			$args[$i+4][0] = "Pr&oacute;xima data em que ser&aacute; cobrada sua assinatura para mais $period meses de benef&iacute;cios avan&ccedil;ados.";
			$args[$i+4][1] = $next_pay_date;
			$plano = $plan_data[5];
		}
		$this->write_table($args);
		echo "<p>$plano</p>";
	}
	public function our_services() {
		$header = array();
		$header[0] = "<center><p valign='middle'>Item</p></center>";
		$header[1] = "<center><p>Plano b&aacute;sico (gratuito)</p></center>";
		$header[2] = "<center><p>Assinatura <a href='bonus_mensais.php'>gr&aacute;tis</a>. Assinatura <a href='assinar.php'>paga.</a></center>";
		$args = array();
		$args[0][0] = "Respostas ilimitadas por m&ecirc;s";
		$args[1][0] = "Uma resposta por pessoa<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Este site cont&eacute;m um sistema autom&aacute;tico eficiente que impede que uma pessoa responda mais de uma vez a uma mesma enquete ou teste. Quando algu&eacute;m responde a uma enquete de novo, sua resposta &eacute; editada, e a nova resposta substitui a anterior. Testes n&atilde;o podem ser respondidos mais de uma vez.'></span>";
		$args[2][0] = "Desativar e reativar enquete<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Voc&ecirc; pode desativar e reativar sua enquete ou teste a qualquer momento. A enquete/teste desativada &eacute; vis&iacute;vel somente para voc&ecirc;. Este recurso pode ser usado para se estabelecer data de t&eacute;rmino para sua enquete ou teste.'></span>";
		$args[3][0] = "Suporte ao cliente<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='O suporte ao cliente ocorrer&aacute; nos dias de semana &agrave; noite e nos fins de semana de dia e de noite. Nunca de madrugada. E ser&aacute; feito por e-mail ou por meio da p&aacute;gina do Facebook cujo link est&aacute; no rodap&eacute; desta p&aacute;gina.'></span>";
		$args[4][0] = "Exibi&ccedil;&atilde;o de enquetes e testes modelo<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='O menu principal deste site cont&eacute;m o item Enquetes Modelo, onde voc&ecirc; ter&aacute; acesso a enquetes e testes bem elaborados para te dar uma luz sobre como criar uma enquete bem feita ou um teste v&aacute;lido, caso voc&ecirc; n&atilde;o saiba como fazer isso.'></span>";
		$args[5][0] = "Destaques do momento<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Se voc&ecirc; conseguir divulgar bem seu conte&uacute;do (texto/&aacute;udio cient&iacute;fico, de opini&atilde;o, enquete, teste), ele poder&aacute; aparecer entre os conte&uacute;dos que s&atilde;o destaque do momento, os quais s&atilde;o exibidos em todas as p&aacute;ginas deste site. Por&eacute;m, a sua divulga&ccedil;&atilde;o do seu conte&uacute;do &eacute; muito mais significativa para a visibilidade do mesmo do que ele aperecer nos destaques do momento.'></span>";
		$args[6][0] = "Esconder resultados parciais<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Esta op&ccedil;&atilde;o est&aacute; dispon&iacute;vel apenas para assinantes. Em muitas situa&ccedil;&otilde;es, &eacute; interessante para o criador da enquete ou teste esconder o conhecimento revelado nos resultados dos mesmos, como por exemplo no caso de n&atilde;o se querer que concorrentes tenham acesso a eles. No caso de testes com resultados escondidos, quem faz o teste poder&aacute; ver somente sua pontua&ccedil;&atilde;o e as respostas certas.'></span>";
		$args[7][0] = "Conte&uacute;do privado<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='O criador de um conte&uacute;do (texto/&aacute;udio cient&iacute;fico, de opini&atilde;o, enquete, teste) pode querer que apenas um grupo restrito de pessoas tenha acesso a ele, como por exemplo no caso em que uma enquete &eacute; direcionada a moradores de um condom&iacute;nio, n&atilde;o podendo pessoas que n&atilde;o residem nele respond&ecirc;-la. Assim, o criador da enquete a torna privada, para que ela n&atilde;o apare&ccedil;a entre as enquetes destaque do momento, em resultados de busca neste site e para que nem possam ser achadas no Google, e assim ela a divulga somenta para seu p&uacute;blico alvo. Op&ccedil;&atilde;o dispon&iacute;vel somente para assinantes.'></span>";
		$args[8][0] = "Exibir seu an&uacute;ncio<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Somente assinantes podem exibir um an&uacute;cio seu junto aos seus conte&uacute;dos (textos/&aacute;udios, enquetes e testes). Seu an&uacute;ncio dever&aacute; ser uma imagem que voc&ecirc; cadastra ao atualizar seus dados ou ao criar um conte&uacute;do. ".$this->html_encode("Se você fornecer um site na criação de conteúdo ou na atualização de dados, seu anúncio será um link para esse site.")."'></span>";
		$args[9][0] = "Baixar resultados da enquete/teste<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Somente assinantes podem baixar os resultados parciais ou finais de sua enquete e/ou teste. Os resultados s&atilde;o baixados no formato PDF e podem ser baixados tamb&eacute;m neste formato resultados por grupos de pessoas.'></span>";
		$args[10][0] = "Excluir nossos an&uacute;ncios<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Na assinatura gratuita, os nossos an&uacute;ncios n&atilde;o s&atilde;o exclu&iacute;dos de seus conte&uacute;dos. Isso acontece somente na assinatura paga, e de acordo com o per&iacute;odo de pagamento escolhido.'></span>";
		$args[11][0] = "Imagens no texto<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Quanto voc&ecirc; escreve um texto que ser&aacute; avaliado por meio de testes e ou enquetes, voc&ecirc; poder&aacute; colocar imagens ilustrativas espalhadas no mesmo.'></span>";
		$args[12][0] = "Seu conte&uacute;do no seu site<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' data-html='true' title='Voc&ecirc; pode hospedar sua enquete e/ou teste da Web Enquetes no seu site, baixando o HTML do mesmo e colando-o em p&aacute;ginas do seu site. Quanto a textos, para que as imagens do mesmo apare&ccedil;am corretamente no seu site, voc&ecirc; deve copi&aacute;-lo do seu formul&aacute;rio de edi&ccedil;&atilde;o, e n&atilde;o da p&aacute;gina onde ele aparece, e col&aacute;-lo numa p&aacute;gina sua. Cabe a voc&ecirc; decidir como o seu conte&uacute;do na Web Enquetes ser&aacute; exibido no seu site. A diferen&ccedil;a entre ser usu&aacute;rio b&aacute;sico e ser assiante refere-se somente a enquetes e testes, neste ponto.'></span>";
		$args[13][0] = "Resultados por grupos<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Quando uma enquete/teste tem mais de uma pergunta, &eacute; poss&iacute;vel aplicar os resultados parciais por grupos, escolhendo-se uma das op&ccedil;&otilde;es de resposta a qualquer pergunta nos resultados parciais da enquete/teste (que &eacute; o grupo escolhido) e vendo como as pessoas que escolheram tal resposta responderam &agrave;s outras perguntas.'></span>";
		for ($i = 0; $i < 11; $i++) {
			if ($i < 6) {
				$args[$i][1] = "<span class='glyphicon glyphicon-ok' style='color:#00CC00'></span>";
			} else $args[$i][1] = "<span class='glyphicon glyphicon-remove' style='color:#CC0000'></span>";
			if ($i < 10) {
				$args[$i][2] = "<span class='glyphicon glyphicon-ok' style='color:#00CC00'></span>";
			} else $args[$i][2] = "De acordo com o per&iacute;odo de pagamento. <a href='assinar.php'>Saiba mais</a>";
		}
		$args[11][1] = "At&eacute; uma imagem para upload<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='left' title='".$this->html_encode("Você poderá fazer upload de no máximo uma imagem para ser usada no seu texto, embora você possa também usar imagens hospedadas em outros servidores sem limites, mas respeitando os devidos direitos autorais.")."'></span>";
		$args[11][2] = "At&eacute; seis imagens para upload<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='left' title='".$this->html_encode("Você poderá fazer upload de até seis imagens para serem usadas no seu texto, embora você possa também usar imagens hospedadas em outros servidores sem limites, mas respeitando os devidos direitos autorais.")."'></span>";
		$args[12][1] = "Padr&atilde;o<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='left' title='No plano b&aacute;sico, a enquete/teste a ser hospedada no seu site, caso voc&ecirc; tenha um, aparecer&aacute; com todas as perguntas de uma vez, caso ela tenha mais de uma pergunta, o que tomar&aacute; um espa&ccedil;o consider&aacute;vel da p&aacute;gina do site onde ela aparece. Al&eacute;m disso, quando uma pessoa responde a essa enquete/teste no seu site, ela &eacute; redirecionada para fora dele, indo aos resultados parciais da mesma no site da Web Enquetes. A marca da Web Enquetes tamb&eacute;m &eacute; exibida junto com a enquete/teste hospedada.'></span>";
		$args[12][2] = "Sob medida<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='left' title='Como assinante, quando voc&ecirc; cria uma enquete/teste de mais de uma pergunta, a vers&atilde;o dela hospedada no seu site exibir&aacute; uma pergunta de cada vez, ocupando pouco espa&ccedil;o nele. Al&eacute;m disso, quando algu&eacute;m termina de responder &agrave; sua enquete/teste no seu site, ela permanece nele, e n&atilde;o h&aacute; redirecionamento para a Web Enquetes. A marca da Web Enquetes n&atilde;o aparece com a enquete/teste hospedada.'></span>";
		$args[13][1] = "B&aacute;sico<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='left' title='Em enquetes/testes de mais de uma pergunta, voc&ecirc; pode escolher apenas uma das respostas a qualquer pergunta para saber como as pessoas que escolheram tal resposta responderam &agrave;s outras perguntas.'></span>";
		$args[13][2] = "Avan&ccedil;ado<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='left' title='Em enquetes/testes de mais de uma pergunta, nos resultados parciais, voc&ecirc; pode escolher mais de uma resposta a quaisquer perguntas para ver como as essoas que escolheram tais respostas selecionadas responderam &agrave;s outras perguntas.'></span>";
		$this->write_table($args, array("header" => $header));
	}
}
?>