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
	$we->valida_enquete1();
	$status = $service->get_free_months();
	if ($service->status == '') {
		$status = $service->change_status();
	}
	if ($status != '') {
		echo "<p><span class='status2'>$status</span></p>";
	}
	if (!isset($idEnquete)) $idEnquete = $_GET['ide'];
	$idc = (isset($_POST['cd_categoria'])) ? $_POST['cd_categoria'] : $_GET['idc'];
	require_once "create_html.php";
	$design = new Create_HTML($idEnquete, $idc);
	$design->con = $we->con;
	$design->idu = $we->idu;
	$design->select("select cd_usuario from enquete where idEnquete = $idEnquete", array("cdu"));
	if ($idEnquete === NULL || $cdu == $design->idu) {
		$design->formTabela1 = Dados_webenquetes::$formTabela1;
		$design->formTabela2 = Dados_webenquetes::$formTabela2;
		$design->formTabela3 = Dados_webenquetes::$formTabela3;
		$design->formTabela4 = Dados_webenquetes::$formTabela4;
		$design->formTabela5 = Dados_webenquetes::$formTabela5;
		
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
		$quantTabela2 = $design->formTabela4[8];
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
		$we->select("select aceito from cliente where idCliente = ".$_SESSION[$idSession], array("aceito"));
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
				alert(xhr.responseText);
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
