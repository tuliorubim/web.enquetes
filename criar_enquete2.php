<?php
session_start();
include "bd.php";
$idSession = 'user';
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
	<form name='form' method='post' enctype='multipart/form-data'>
	<div class="col-md-6">
    <h2>CRIE SUA ENQUETE</h2>
	<?php
	$edit = 0;
	$enqueteAnterior = $_POST['idEnquete'];
	$quantTabela2 = 20;
	
	$variaveis = array("idCategoria", "categoria");
	$labels = array("", "Categoria: ");
	$inputs = array("hidden", "");
	$properties = "class='form-control input-lg'";
	$formTabela1 = array($variaveis, NULL, $labels, $inputs, NULL, "categoria", NULL, $properties);
	
	$variaveis = array("idEnquete", "cd_categoria", "cd_usuario", "enquete", "introducao", "dt_criacao", "disponivel", "duracao", "code", "url", "esconder", "hide_results", "acima_de_cem");
	$tipos = array('integer', 'integer', 'integer', 'varchar', 'varchar', 'datetime', 'boolean', 'integer', 'varchar', "varchar", 'boolean', 'boolean', 'boolean');
	$labels = array("", "", "", "Enquete", 'Introdu&ccedil;&atilde;o', "", '', '', '', '', '', '', '');
	$inputs = array("hidden", "hidden", "hidden", "text", "textarea", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden");
	$maxlengths = array("", "", "", "64", "1024", '', '', '', '12', '256', '', '', '');
	$tabela = "enquete";
	$formTabela2 = array($variaveis, $tipos, $labels, $inputs, array(), $tabela, $maxlengths, $properties);
	
	$variaveis = array("idPergunta", "cd_enquete", "pergunta", "multipla_resposta");
	$tipos = array("integer", "integer", "varchar", "boolean");
	$labels = array("", "", "Pergunta", "Permite escolha de mais de uma op&ccedil;&atilde;o de resposta: ");
	$inputs = array("hidden", "hidden", "text", "checkbox");
	$maxlengths = array("", "", "1024", "");
	$tabela = "pergunta";
	$enderecos = array();
	$properties = array('', '', "$properties", "onclick = 'this.value = this.checked;'");
	$formTabela3 = array($variaveis, $tipos, $labels, $inputs, $enderecos, $tabela, $maxlengths, $properties);
	$variaveis2 = array("idResposta", "cd_pergunta", "resposta");
	$tipos2 = array("integer", "integer", "varchar");
	$labels2 = array("", "", "Op&ccedil;&atilde;o de resposta");
	$inputs2 = array("hidden", "hidden", "textarea");
	$maxlengths2 = array("", "", "1024");
	$tabela2 = "resposta";
	$enderecos2 = array();
	
	$rs = mysqli_query($con, "select idResposta from resposta where cd_pergunta = $indEdit");
	$properties = array('', '', "class='form-control input-lg'");
	$ini = 3;
	$excluirResposta = ValorSelecionado ($POST, $rs, "idResposta", "excluir", "Excluir");
	$g = $excluirResposta[0];
	$row = NULL;
	if ($rs && mysqli_num_rows($rs) > 0) {
		mysqli_data_seek($rs, 0);
		$row = mysqli_fetch_array($rs);
	}
	?>
	<script language='javascript'>
	$(document).ready( function () {
	<?php	
	$ini = 2;
	for ($i = 0; $i <= $quantTabela2; $i++) {
	?>	
		$("#resposta<?php echo $i;?>").focus(function () {
		<?php 
		if ($_POST['butPres'] === "Select" || $excluirResposta[1] || $editarPergunta[1]) { 
			if ($ini === 2) $ini = 1;
		?>
			$("#exclude").attr("name", "delete<?php $row['idResposta'];?>");
		<?php
		}
		if ($i >= $ini) {
		?>
			Adiciona(this);	
		<?php 
		} 
		?>	
		});
	<?php
	}
	?>
	});
	</script>
	<?php	
	$formTabela4 = array($variaveis2, $tipos2, $labels2, $inputs2, $enderecos2, $tabela2, $maxlengths2, $properties, $quantTabela2);
	$SESSION = $_SESSION;
	$POST = $_POST;
	$focus = array(0, -1);
	$args = array();
	$args2 = array();
	$inputs2 = array();
	
	$sql = "select * from categoria order by categoria";
	$select = array($sql, "select");
	$POST['butPres'] = "Select";
	
	$inds = adminPage ($POST, $_FILES, $SESSION, $formTabela1, array(), array(), $select, false, array(0, 0));
	
	$idc = $_GET['idc'];
	if ($idc !== NULL) {
		select("select categoria from categoria where idCategoria = $idc", array('categoria'));
	?>
		<script language="javascript">
			$("input[name='idCategoria']").val('<?php echo $idc; ?>');
			$("#categoria").val("<?php echo $categoria; ?>");
		</script>
	<?php	
	}
	$select[1] = "form";
	
	$ep = NULL;
	if ($POST["idPergunta"] !== NULL ) {
		$rs = mysqli_query($con, "select idResposta from resposta where cd_pergunta = ".$POST["idPergunta"]);
		$ep = ValorSelecionado($POST, $rs, "idResposta", "excluir", "Excluir");
	}
	$n = $ep[0];
	$rs = mysqli_query($con, "select idPergunta from pergunta where cd_enquete = ".$POST['idEnquete']);
	
	$editarPergunta = ValorSelecionado ($POST, $rs, "idPergunta", "edit", "Editar");
	$ie = $editarPergunta[0];
	$idEnquete = $_GET['ide'];
	if ($_POST['novaPergunta'] !== "Nova Pergunta" && $POST['del'] !== "Excluir Pergunta" && $POST["excluir$n"] !== "Excluir" && $POST["edit".$ie] !== "Editar") {
		$POST["butPres"] = $_POST["butPres"];
	}	
	if ($POST['butPres'] === NULL && $idEnquete !== NULL) {
		$POST['butPres'] = "Select"; 	
	}
	$enq = $_POST['enquete'];
	if (empty($enq) && $idEnquete !== NULL) 
		select("select enquete from enquete where idEnquete = $idEnquete", array('enq'));	
	if ($idEnquete === NULL)
		$select[0] = "select * from enquete where enquete like '%$enq%' and cd_usuario = $idu";
	else $select[0] = "select * from enquete where idEnquete = $idEnquete and cd_usuario = $idu";	
	$POST["cd_usuario"] = $_SESSION[$idSession];
	$POST["cd_categoria"] = $_POST["idCategoria"];
	if ($POST['butPres'] === 'Save') {
		if ($POST['idEnquete'] == NULL) $POST["dt_criacao"] = date("$dateformat $timeformat");
		if ($POST['code'] == NULL) $POST['code'] = codeGenerator();
		if ($POST['acima_de_cem'] == NULL) $POST['acima_de_cem'] = 0;
		$POST["disponivel"] = 1;
	}
	if ($POST['butPres'] === 'Delete' && $POST['idEnquete'] !== NULL)
		mysqli_query($con, "delete from voto where cd_enquete = ".$POST['idEnquete']);
	
	$inds2 = adminPage ($POST, $_FILES, $SESSION, $formTabela2, array(), array(), $select, false, $inds);
	
	addForeignKey("enquete", "cd_categoria", "categoria", "idCategoria");
	addForeignKey("enquete", "cd_usuario", "usuario", "idUsuario");
	$color = 'green';
	if (strpos ($status, "success") !== FALSE) {
		if (strpos ($status, "stored") !== FALSE) {
			$status = "Sua enquete foi criada ou atualizada corretamente.";
		} elseif (strpos ($status, "deleted") !== FALSE) {
			$status = "Sua enquete foi excluida corretamente.";
		}
	} elseif (strpos ($status, "Error on selecting") !== FALSE) {
		$color = 'red';
		$status = "N&atilde;o h&aacute; enquetes suas com a palavra chave digitada.";
	}
	write_status($color);
	$rs = mysqli_query($con, "select idPergunta from pergunta where cd_enquete = $idEnquete");
	$editarPergunta = ValorSelecionado ($POST, $rs, "idPergunta", "edit", "Editar");
	$indEdit = $editarPergunta[0];
	if (!$editarPergunta[1] && $POST['idPergunta'] != NULL && $enqueteAnterior === $idEnquete) $indEdit = $POST['idPergunta'];
	if (!empty($enq)) { ?>
	 <script language='javascript'>//$("input[name='enquete']").val('<?php //echo $enq; ?>');</script>	
	<?php
	}
	if (!empty($idEnquete)) {
		$sql = "select idCategoria, categoria from categoria where idCategoria = $cd_categoria";
		select ($sql, array('idc', 'cat'));
	?>	
		<script language='javascript'>
			$("input[name='idCategoria']").val('<?php echo $idc; ?>');
			$("#categoria").val("<?php echo $cat; ?>");
		</script>
	<?php
	}
	if ($_POST['novaPergunta'] == "Nova Pergunta") $POST["butPres"] = "New";
	$enquete = $_POST['enquete'];
	$select[0] = "select p.*, r.* from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta where p.cd_enquete = $idEnquete and p.idPergunta = $indEdit";
	$POST['cd_enquete'] = $idEnquete;
	if ($POST['del'] == "Excluir Pergunta") $POST["butPres"] = $_POST["butPres"];
	if ($POST["multipla_resposta"] == NULL || $POST["multipla_resposta"] == false) {
		$POST["multipla_resposta"] = 0;
	}
	$inds3 = adminPage ($POST, $_FILES, $SESSION, $formTabela3, $formTabela4, array(), $select, false, $inds2);
	?>
	<script language="javascript">
		$("input[name='pergunta']").after('<button type="submit" class="nova_pergunta" name="novaPergunta" value="Nova Pergunta"><img src="img/botao-incluir.png" alt="Botão Adicionar" title="Adicionar nova pergunta" class="btn-add"><span style="font-size: 18px;"> Nova pergunta</button>');
	</script>
	<div style="margin-top:10px;"><input type='hidden' name='butPres' value=''/>
	<input type='hidden' name='del' value=''/>
	<input type='button' class="btn btn-primary estilo-modal" name="novo" value="Novo" onClick="Novo()">
	<input type='button' class="btn btn-primary estilo-modal" name='salvar' value='Salvar'/>
	<input type='button' class="btn btn-primary estilo-modal" name='excluir' value='Excluir Enquete' onclick='document.form.del.value = this.value; ValidateExclusion(document.form.idEnquete);'/>
	<input type='button' class="btn btn-primary estilo-modal" name='excluirPergunta' value='Excluir Pergunta' onclick='document.form.del.value = this.value; ValidateExclusion(document.form.idPergunta);'/>
	<input type='button' class="btn btn-primary estilo-modal" name='selecionar' value='Selecionar' onclick='Selec(document.form.enquete)'/>
	<?php 
	if ($POST['disponivel'] !== NULL && $POST['disponivel'] == 0) {
	?>
	<!--<a href='ativar.php' class="btn btn-primary estilo-modal2" title='Clique aqui para iniciar o processo de ativa&ccedil;&atilde;o desta enquete.' value='Ativar'>Ativar</a>-->
	<?php } ?>
	</div>
	</div>
	<div class="col-md-6">
	<div style='margin-top: 16%;'>
	<?php
	select("select cd_servico from cliente where idCliente = $idu", array('cd_servico'));
	if ($idEnquete !== NULL) {
	?>
	<div id="poll_options"><center>OP&Ccedil;&Otilde;ES</center>
	<ul id="menu_adm_poll" type="none">
		<li><a href='enquete.php?ide=<?php echo $idEnquete;?>'>Visualizar esta enquete</a></li>
		<li><a href='#' id='baixar' data-toggle='tooltip'>Baixar HTML desta enquete</a></li>
		<?php 
		if ($cd_servico > 0) {
		?>
		<!---<li><a href="pdf_result.php?ide=<?php // echo $ide;?>" id="pdf_result">Gerar PDF do resultado</a></li>-->
		<li>Esconder resultados parciais <input type="checkbox" name="hide_results2"/></li>
		<li>Esconder enquete <input type="checkbox" name="hide_poll"/></li>
		<?php } ?>
	</ul></div>
	<?php
	}
	if ($_POST['novaPergunta'] === "Nova Pergunta" || $POST['butPres'] === "Select" || $POST['butPres'] === "Save" || $POST['del'] === "Excluir Pergunta" || $idEnquete !== NULL) {
		$banco = 'enquetes';
		$fields = "select p.pergunta, r.resposta from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta where p.cd_enquete = $idEnquete order by p.idPergunta";
		$i = 0;
		$conteudo = array();
		$rs = mysqli_query($con, "select p.idPergunta from pergunta p inner join enquete e on p.cd_enquete = e.idEnquete where idEnquete = $idEnquete");
		
		while ($rs && $row = mysqli_fetch_array($rs)) {
			$c = $row["idPergunta"];
			$conteudo[$i] = "<input type='submit' name='edit$c' value='Editar'>";
			$i++;
		}
		$incluiConteudo = array("content" => $conteudo, "beforeAfter" => "after", "level" => 0);
		echo designBlocks (array("database" => $banco, "fields" => $fields, "includeContent" => $incluiConteudo));
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
var cds = <?php echo (isset($cd_servico)) ? $cd_servico : 0;?>;
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
	for (i = ini; i <= 20; i++){
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
if (document.form.resposta0) {
	conditions["resposta0"] = 1;
} else conditions["resposta2"] = 1;
$(document).ready(function () {
	$('[data-toggle="tooltip"]').tooltip();
	if ($("#hide_results").val() == 1)
		$("input[name='hide_results2']").prop("checked", "checked");
	if ($("#esconder").val() == 1)
		$("input[name='hide_poll']").prop("checked", "checked");
	$("input[name='salvar']").click(function () {
		valid = validateForm (document.form, conditions);
		if (valid) ValidaGravacao();
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
					alert("Sua enquete foi escondida com sucesso.");
				} else if (result['status'] == 'esconder0') {
					alert("Sua enquete foi tornada p\u00fablica com sucesso.");
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
