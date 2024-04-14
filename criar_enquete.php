<?php
include "criar_enquete_modelo.php";
$idSession = 'user';
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php include "head.php"; ?>
<body>
<?php 
include "header.php"; 
?> 

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
	<form name='form' method='post' action="criar_enquete_controle.php" enctype='multipart/form-data'>
	<div class="col-md-6">
    <h2>CRIE SUA ENQUETE</h2>
	<div class="fb-like" data-href="https://www.facebook.com/WebEnquetesEPesquisas/" data-width="100" data-layout="standard" data-action="like" data-size="small" style='margin: 3px;' data-show-faces="true" data-share="false">
	</div>
	<?php
	require_once "funcoes/funcoesDesign.php";
	class CriarEnquete extends DesignFunctions {
		use Dados_webenquetes;
		public $select;
		public $idc;
		public $idEnquete;
		public $idu;
		public function __construct($ide, $idc, $idu, $con, $ft1, $ft2, $ft3, $ft4, $ft5) {
			$this->idEnquete = (!isset($ide)) ? 0 : $ide;
			$this->idc = (!isset($idc)) ? 0 : $idc;
			$this->idu = (!isset($idu)) ? 0 : $idu;
			$this->con = $con;
			self::$formTabela1 = $ft1;
			self::$formTabela2 = $ft2;
			self::$formTabela3 = $ft3;
			self::$formTabela4 = $ft4;
			self::$formTabela5 = $ft5;
		}
		public function criar_usuario() {
			global $status;
			global $we;
			$idu = $this->idu;
			$email = $_GET['email'];
			$senha = $this->codeGenerator();
			$dateformat = "d/m/Y";
			$timeformat = "H:i:s";
			$data = date("$dateformat $timeformat");
			$this->save('cliente', array('idCliente', 'usuario', 'senha', 'data_cadastro', 'permitir_email'), array('integer', 'varchar', 'varchar', 'datetime', 'boolean'), array($idu, $email, $senha, $data, 1));
			$we->sendEmail (array('email' => 'tfrubim@gmail.com', 'name' => 'Web Enquetes', 'subject' => 'Sua senha Web Enquetes', 'message' => "Sua senha para sua area restrita neste site: $senha"), array($email));
			if (!mysqli_error($this->con) && strpos($status, "corretamente")) {
				$status = $this->html_encode("Você acaba de cadastrar seu email e uma senha foi gerada e enviada para este email para que você tenha acesso à área restrita deste site.");
				$this->write_status();
			}
		}
		public function form_categorias () {
			$sql = "select * from categoria order by categoria";
			$select = array($sql, "select");
			$select[5] = true;
			
			$inds = $this->formGeral ($_SESSION, self::$formTabela1, array(), array(), $select, false, array(0, 0), true);
			$idc = $this->idc;
			$idEnquete = $this->idEnquete;
			if ($idc === NULL) $idc = 0;
			$args = [];
			if ($idc !== 0 || $idEnquete !== NULL) {
				if ($idEnquete !== NULL) {
					$sql = "select c.idCategoria, c.categoria from categoria c left join enquete e on c.idCategoria = e.cd_categoria where e.idEnquete = $idEnquete";
				} else $sql = "select idCategoria, categoria from categoria where idCategoria = $idc";	
				$args = $this->select($sql, array(), true);
				if (!empty($args)) {
			?>
				<script language="javascript">
					$("input[name='idCategoria']").val('<?php echo $args[0]['idCategoria']; ?>');
					$("#categoria").val("<?php echo $args[0]['categoria']; ?>");
				</script>
			<?php
				}	
			}
			$this->idc = (!empty($args)) ? $args[0]['idCategoria'] : 0;
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
	?>
			<div style="margin-top:10px;"><b>Este question&aacute;rio &eacute; composto de:</b><br />
			<input type="radio" name="enq_ou_prova" id="enquete_ou_prova1" value='1' /> <b>Somente enquetes</b><br />
			<input type="radio" name="enq_ou_prova" id="enquete_ou_prova2" value='2' /> <b>Somente testes</b><br />
			<input type="radio" name="enq_ou_prova" id="enquete_ou_prova3" value='3' checked="checked"/> <b>Ambos</b><br /></div>
			<script language="javascript">
			function EnqTestMix (q) {
				switch (q) {
					case '1' :
						$("#enquete_ou_teste0").prop("checked", true);
						$('#d_tempo_teste').css('display', 'none');
						EnableDisableTest("none");
						$("#enquete_ou_teste").css("display", "none");
						break;
					case '2' :
						$("#enquete_ou_teste1").prop("checked", true);
						$('#d_tempo_teste').css('display', '');
						EnableDisableTest("");
						$("#enquete_ou_teste").css("display", "none");
						break;
					case '3' :
						$("#enquete_ou_teste0").prop("checked", true);
						$('#d_tempo_teste').css('display', 'none');
						d = ($("#cd_resposta_certa").val() == 0) ? 'none' : '';
						EnableDisableTest(d);
						$("#enquete_ou_teste").css("display", "");
						break;
				}
			} 
			
			$(function () {
				/*$("#categoria").change(function () {
					try {
						seleciona($(this).val(), 1, 2, 0, 0);
					} catch (e) {
						alert(e.message);
					}
					alert($(this).val());
				});*/
				q = $("input[name='enquete_ou_prova']").val();
				q = (q == '') ? '3' : q;
				$("#enquete_ou_prova"+q).prop("checked", true);
				EnqTestMix (q);
				$("input[name='enq_ou_prova']").change(function () {
					EnqTestMix ($(this).val());
				});
			});
			</script>
	<?php			
			$inds = $this->formGeral ($_SESSION, self::$formTabela2, array(), array(), $select, false, $inds);
			echo "<script>$('#d_tempo_teste').css('display', 'none');</script>";
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
				$select[0] = "select idCliente, logo, logoReduzida from cliente where idCliente = $idu";
				$select[5] = true;
				$inds = $this->formGeral ($_SESSION, self::$formTabela5, array(), array(), $select, false, $inds);
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
			if (array_key_exists('np', $_GET) && $_GET['np'] == "true") {
				$select[5] = false;
			} elseif (array_key_exists('idp', $_GET) && isset($_GET['idp'])) {
				$indEdit = $_GET['idp'];
			} elseif ($idEnquete > 0) {
				$rs = mysqli_query($this->con, "select idPergunta from pergunta where cd_enquete = $idEnquete order by idPergunta");
				$row = mysqli_fetch_array($rs);
				$indEdit = (!empty($row['idPergunta'])) ? $row['idPergunta'] : 0;
			}
			$select[0] = "select p.*, r.* from pergunta p left join resposta r on p.idPergunta = r.cd_pergunta where p.cd_enquete = $idEnquete and p.idPergunta = $indEdit order by r.idResposta";
			$args = $this->select("select count(dt_voto) as dez from voto where cd_enquete = $idEnquete");
			$excluir = true;
			if ($args[0]['dez'] >= 10) {
				$excluir = false;
				$this->formTabela4[9] = 'readonly';
			}
	?>
			<div id="enquete_ou_teste">
			<b>Esta pergunta ser&aacute;:</b><br />
			<input type="radio" name="enquete_ou_teste" id="enquete_ou_teste0" value="0" checked="checked"/> <b>Enquete</b><br />
			<input type="radio" name="enquete_ou_teste" id="enquete_ou_teste1" value="1" /> <b>Teste</b>
			</div>
			<div id="teste3"></div>
			<script language="javascript">
			function EnableDisableTest(d) {
				$("#d_valor").css("display", d);
				$("#d_multipla_resposta").css("display", (d == "") ? "none" : "");
				for (var i = 0; i <= 200; i++) {
					$("#d_cd_resposta"+i).css("display", d);
				}
			}
			$(function () {
				//nulo = [0, null];
				d = ($("#cd_resposta_certa").val() == 0 && q != 2) ? 'none' : '';
				//if ($("input[name='enquete_ou_prova']").val() == '') {
				EnableDisableTest(d);
				//}
				if (d == '') {
					$("#enquete_ou_teste1").prop("checked", true);
					for (i = 0; $("#idResposta"+i).val() != null; i++) {
						if ($("#idResposta"+i).val() == $("#cd_resposta_certa").val()) {
							break;
						}
					}
					$("#cd_resposta"+i).prop("checked", true);
					$("input[name='cd_resposta']").val(i);
				}
				$("input[name='enquete_ou_teste']").change(function () {
					d = ($(this).val() == '0') ? 'none' : '';
					EnableDisableTest(d);
				});
				//alert($("input[name='cd_resposta']").val());
				$("input[name='cd_resposta']").click(function () {
					rid = $(this).attr("id");
					id = rid.substring(11);
					$(this).val(id);
				}); 
				$("textarea").on("keyup", function () {
					if ($("#enquete_ou_teste1").prop("checked")) {
						if ($(this).attr('name').indexOf("resposta") > -1) {
							i = Number($(this).attr("name").substring(8));
							ord = (!document.form.resposta0) ? i+96 : i+97;
							char = String.fromCharCode(ord);
							$("input[name='letra"+i+"']").val(($(this).val().length > 0) ? char : '');
							//$("#teste3").html($("input[name='letra"+i+"']").val());
						}
					}	
				});
			});
			</script>
	<?php
			$inds = $this->formGeral ($_SESSION, self::$formTabela3, self::$formTabela4, array(), $select, false, $inds);
			$this->select = $select;
			return array($inds, $excluir);
		}
		public function poll_for_editing () {	
			$idEnquete = (!empty($this->idEnquete)) ? $this->idEnquete : -1;
			if ((array_key_exists('np', $_GET) && $_GET['np'] === "true") || $this->select[5] || $idEnquete != -1) {
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
	}
	$we->valida_enquete1();
	$status = $service->get_free_months();
	if ($service->status == '') {
		$status = $service->change_status();
	}
	if ($status != '') {
		echo "<p><span class='status2'>$status</span></p>";
	}
	$GET = $we->array_keys_assign(array('ide', 'idc'), $_GET);
	if (!isset($idEnquete)) $idEnquete = $GET['ide'];
	$idc = (isset($_POST['cd_categoria'])) ? $_POST['cd_categoria'] : $GET['idc'];
	$ft1 = Data_webenquetes::$formTabela1;
	$ft2 = Data_webenquetes::$formTabela2;
	$ft3 = Data_webenquetes::$formTabela3;
	$ft4 = Data_webenquetes::$formTabela4;
	$ft5 = Data_webenquetes::$formTabela5;
	$design = new CriarEnquete($idEnquete, $idc, $we->idu, $we->con, $ft1, $ft2, $ft3, $ft4, $ft5);
	$design->select("select cd_usuario from enquete where idEnquete = ".((isset($idEnquete)) ? $idEnquete : 0), array("cdu"));
	if ($idEnquete === NULL || $cdu == $design->idu) {
		if ($_GET['news'] === 'NEWS')
			$design->criar_usuario();
		$inds = $design->form_categorias();
		$inds = $design->form_enquete($inds);
		$inds = $design->upload_ad($inds, $cd_servico);
		$inds = $design->form_pergunta_respostas($inds, $cd_servico);
		
		$excluir = $inds[1];
		$ini = 2;
		if ($design->select[5]) $ini = 1;
		?>
		<script language="javascript">
		$(function () {
		<?php
		$quantTabela2 = $ft4[8];
		for ($i = $ini; $i <= $quantTabela2; $i++) {
		?>
			$("#resposta<?php echo $i;?>").focus(function () {
				Adiciona(this);
			});
		<?php
		}
		?>
		});
		<?php if ($idEnquete !== NULL) { ?>
			$("input[name='pergunta']").after('<a href="criar_enquete.php?ide=<?php echo $idEnquete;?>&np=true" class="nova_pergunta" name="novaPergunta"><img src="img/botao-incluir.png" alt="Botão Adicionar" title="Adicionar nova pergunta" class="btn-add"><span style="font-size: 18px;"> Nova pergunta</a>');
		<?php } ?>	
		</script>
		<?php
		$design->select("select aceito from cliente where idCliente = ".$_SESSION[$idSession], array("aceito"));
		if (!$aceito) {
		?>
			<br>
			<input type="checkbox" name="aceitar_termos"> <label for="aceitar_termos">Aceitar os <a href="termos_uso.php">Termos de Uso.</a></label>
			<input type="hidden" name="aceitar" value="">
		<?php } ?>
		<div style="margin-top:10px;"><input type='hidden' name='butPres' value=''/>
		<input type='hidden' name='del' value=''/>
		<a href='criar_enquete.php' class="btn btn-primary estilo-modal">Novo</a>
		<input type='button' class="btn btn-primary estilo-modal" name='salvar' value='Salvar'/>
		<?php if ($excluir) { ?>
			<input type='button' class="btn btn-primary estilo-modal" name='excluir' value='Excluir Enquete' onclick='document.form.del.value = this.value; ValidateExclusion(document.form.idEnquete);'/>
			<input type='button' class="btn btn-primary estilo-modal" name='excluirPergunta' value='Excluir Pergunta' onclick='document.form.del.value = this.value; ValidateExclusion(document.form.idPergunta);'/>
		<?php } ?>
		</div>
		</div>
		<div class="col-md-6">
		<div style='margin-top: 16%;'>
		<?php
		if ($idEnquete !== NULL) {
		?>
		<div id="poll_options"><center>OP&Ccedil;&Otilde;ES</center>
		<ul id="menu_adm_poll" type="none">
			<li><a href='enquete.php?ide=<?php echo $idEnquete;?>'>Visualizar esta enquete</a></li>
			<li><a href='#' id='baixar' data-toggle='tooltip'>Baixar HTML desta enquete</a></li>
			<li>ENQUETE N&Atilde;O ENCERRADA <input type="checkbox" name="ativada"/></li>
			<?php 
			if ($cd_servico > 0) {
			?>
			<li><a href="resultados_parciais.php?ide=<?php echo $idEnquete;?>" id="pdf_result">Gerar PDF's de resultados</a></li>
			<li>Esconder resultados parciais <input type="checkbox" name="hide_results2"/></li>
			<li>Enquete privada <input type="checkbox" name="hide_poll"/></li>
			<?php } ?>
		</ul></div>
		<?php
		}
		$design->poll_for_editing();
	}
?>	
	</div>
	</div>
	</form>	
	<div class="modal fade enter_url" tabIndex=-1 role="dialog" aria-labelledby="mySmallModalLabel">
				
		<button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<form name="url_enquete" method="post">
			<input type="hidden" name="butPres">
			<input type="hidden" name="idEnquete" id="idEnquete">
			<p><input type="radio" name="text_color" checked="checked" value='0'> O texto da enquete ser&aacute; preto.<br>
			<input type="radio" name="text_color" value="1"> O texto da enquete ser&aacute; branco.</p>
			<p><input type="button" class="btn btn-primary estilo-modal" name="enviar" id="enviar" value="Enviar"></p>
		</form>
	</div>
	</div>
</div>
<a id='download' href='#' style="display:none;" download></a>
<script language="javascript">
var np = 0;
var nr = 2;
var ide = <?php echo (isset($idEnquete)) ? $idEnquete : 0;?>;
var quantTabela2 = <?php echo (isset($quantTabela2)) ? $quantTabela2 : 0;?>;
var file = ide+'.txt';
function Oculta() {
	ini = 1;
	valor = null;
	if ($("#form2_0")) {
		ini  = 0;
	}
	valor = $("#resposta"+ini).val();
	while (valor != null && valor != '') {
		ini++;
		valor = $("#resposta"+ini).val();
	}
	if (ini < 3) ini = 3;
	i = ini-1;
	valor = $("#resposta"+i).val();
	if (valor != null && valor != '') ini++;
	for (i = ini; i <= quantTabela2; i++){
		$("#form2_"+i).hide();
	}
	nr = ini-1;
}
Oculta();
function Adiciona(resposta) {
	if (resposta.name == "resposta"+nr) {
		nr++;
		$("#form2_"+nr).show();
	}
}
var conditions = [];
conditions["categoria"] = 1;
conditions["enquete"] = 1;
conditions["pergunta"] = 1;
conditions["resposta1"] = 1;
var aceito = document.form.aceitar_termos;
$("input[name='aceitar_termos']").click(function () {
	document.form.aceitar.value = document.form.aceitar_termos.checked;
});
if (aceito) {
	conditions["aceitar_termos"] = true;
}
if (document.form.resposta0) {
	conditions["resposta0"] = 1;
} else conditions["resposta2"] = 1;
$(document).ready(function () {
	if (novo_usuario && confirm('Sua senha foi enviada para o e-mail que voc\u00ea acabou de fornecer. Voc\u00ea gostaria de receber novidades do seu interesse por e-mail?')) {
		$.ajax({
			url: 'permitir_email.php',
			type: 'POST',
			dataType: 'json',
			data: {
				idu: <?php echo $design->idu;?>
			},
			success: function (ret) {alert(ret["success"]);},
			error: function (xhr, s, e) {
				alert(xhr.responseText);
			}
		});
	}
	$("#introducao").attr("rows", 15);
	$('[data-toggle="tooltip"]').tooltip();
	$("#usar_logo").click(function () { 
		$(this).val($(this).prop("checked"));	
	});
	if (cds == 1) {
		$("#logo").on("change", function () {
			$("#d_usar_logo").css("display", "");
		});
	}
	if ($("#disponivel").val() == 1)
		$("input[name='ativada']").prop("checked", "checked");
	if ($("#hide_results").val() == 1)
		$("input[name='hide_results2']").prop("checked", "checked");
	if ($("#esconder").val() == 1)
		$("input[name='hide_poll']").prop("checked", "checked");
	$("input[name='salvar']").click(function () {
		ep1 = $("input[name='enquete_ou_prova']").val();
		ep2 = $("input[name='enq_ou_prova']").val();
		if ($("#enquete_ou_teste1").prop("checked")) {
			conditions['cd_resposta'] = 10;	
		}
		valid = validateForm (document.form, conditions);
		if (valid) {
			ValidaGravacao();
		}
	});
	$("#baixar").click(function () {
		$.ajax({
			url: 'create_poll_file.php',
			type: 'POST',
			data: {
				ide: ide,
				cds: cds
			},
			success: function (result) {
				if (result['status'] == 'success') {
					$(".enter_url").modal();
				} else if (result['status'] == 'failure') {
					alert("O arquivo da enquete n\u00e3o foi criado.");
				} else {
					alert("Esta enquete n\u00e3o est\u00e1 dispon\u00edvel");
				}
			},
			error: function (xhr, s, e) {
				alert(e);
			}
		});
	});
	$("input[name='text_color']").click(function () {
		$("#enviar").prop("disabled", "disabled");
		pb = this.value;
		$.ajax({
			url: "poll_text_color.php",
			type: "POST",
			dataType: "json",
			data: {
				ide: ide,
				pb: pb
			},
			success: function (result) {
				$("#enviar").removeAttr("disabled");
			},
			error: function (xhr, s, e) {
				alert('erro');
				alert(xhr.responseText);
			}
		}); 
	});
	$("#enviar").click(function () {
		$("#download").attr("href", file);
		document.getElementById("download").click();
		document.getElementById("close").click();
	});
	$("input[value='Delete']").css("display", "none");
	$("input[type='checkbox']").click(function () {
		field = '';
		if ($(this).attr("name") == "hide_results2") {
			field = "hide_results";
		} else if ($(this).attr("name") == "hide_poll") {
			field = "esconder";
		} else if ($(this).attr("name") == "ativada") {
			field = "disponivel";
		}
		hide = ($(this).prop("checked") == true) ? 'true' : 'false';
		$.ajax({
			url: 'esconder.php',
			type: 'POST',
			dataType: 'json',
			data: {
				ide: ide,
				cds: cds,
				field: field,
				hide: hide
			},
			success: function (result) {
				if (result['status'] == 'hide_results1') {
					alert("Os resultados parciais da sua enquete foram escondidos com sucesso.");
				} else if (result['status'] == 'hide_results0') {
					alert("Os resultados parciais da sua enquete foram tornados p\u00fablicos com sucesso.");
				} else if (result['status'] == 'esconder1') {
					alert("Sua enquete tornou-se privada com sucesso. Agora ela n\u00e3o pode ser achada nos mecanismos de pesquisa e nem pode aparecer entre as enquetes de destaque.");
				} else if (result['status'] == 'esconder0') {
					alert("Sua enquete foi tornada p\u00fablica com sucesso. Agora qualquer um pode encontr\u00e1-la.");
				} else if (result['status'] == 'disponivel0') {
					alert("Sua enquete foi desativada com sucesso. Somente voc\u00ea pode v\u00ea-la.");
				} else if (result['status'] == 'disponivel1') {
					alert("Sua enquete foi reativada com sucesso.");
				} else if (result['status'] != '') {
					alert (result['status']);/*"Ocorreu um erro nesta opera\u00e7\u00e3o");*/
				}
			},
			error: function (xhr, s, e) {
				alert(xhr.responseText);
			}
		});
	});
});
</script>

<div class="bkg-enquetes-h">
<?php include "latest_polls.php"; ?>
</div>

<?php include "footer.php"; ?>

</html>
