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
    <ul class="nav nav-pills">
	  <li role="presentation" class="active"><a href="dados.php">Meus Dados</a></li>
	  <li role="presentation"><a href="minhas_enquetes.php">Minhas Enquetes</a></li>
	  <li role="presentation"><a href="criar_enquete.php">Criar Enquete</a></li>
	  <li role="presentation"><a href="index.php?login=off">Sair</a></li>
	</ul>
    </div>
    </div>
    </div>
    <div class="row">
    <div class="col-md-6">
    <h2>MINHAS ENQUETES</h2>
    <?php
	$sql = "select e.idEnquete, e.enquete, cl.cd_servico from enquete e inner join cliente cl on e.cd_usuario = cl.idCliente where e.cd_usuario = $idu order by e.idEnquete";
	$args = select($sql);
	$cd_servico = $args[0][2];
	$html = "<select id='my_polls'>";
	for ($i = 0; $args[$i][0] !== NULL; $i++) {
		$html .= "<option value=".$args[$i][0].">".$args[$i][1]."</option>";
	}
	$html .= "</select>";
	?>
	<ul id="menu_adm_poll">
	<li><a href='criar_enquete.php?ide=0'>Atualizar</a></li>
	<li><a href='enquete.php?ide=0'>Visualizar</a></li>
	<!--<p><a href='#' id='baixar' data-toggle='modal' data-target='.enter_url' onclick='get_url(".$args[$i][0]."); $(\"#erro\").remove();'>Baixar HTML desta enquete</a></p>-->
	<li title='Visualize esta enquete para gerar o arquivo da mesma para download.'>N&atilde;o dispon&iacute;vel para download</li>
	<?php 
	if ($cd_servico > 0) {
	?>
	<li>Resultados parciais <button class="btn btn-primary estilo-modal" disabled="disabled" value="0">Esconder</button></li>
	<li>Enquete <button class="btn btn-primary estilo-modal" disabled="disabled" value="0">Esconder</button></li>
	<?php } ?>
	</ul>
	<div class="modal fade enter_url" tabIndex=-1 role="dialog" aria-labelledby="mySmallModalLabel">
				
		<button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<p>Forne&ccedil;a o endere&ccedil;o completo da p&aacute;gina do seu site onde aparecer&aacute;	esta enquete.</p>
		<form name="url_enquete" method="post">
			<input type="hidden" name="butPres">
			<input type="hidden" name="idEnquete" id="idEnquete">
			<p><input type="text" name="url" id="url" placeholder="Endere&ccedil;o" size="35"></p>
			<p><input type="radio" name="text_color" checked="checked" value='0'> O texto da enquete ser&aacute; preto.<br>
			<input type="radio" name="text_color" value="1"> O texto da enquete ser&aacute; branco.</p>
			<p><input type="button" class="btn btn-primary estilo-modal" name="enviar" id="enviar" value="Enviar"></p>
		</form>
	</div>
	<a id='download' href='#' style="display:none;" download></a>
	<script language="javascript">
		var file = '';
		var cd_servico = <?php echo $cd_servico;?>;
		var criar_enquete_ide = <?php echo strlen("criar_enquete.php?ide=");?>
		var enquete_ide = <?php echo strlen("enquete.php?ide=");?>
		function get_url(ide) {
			$("#idEnquete").val(ide);
			$.ajax({
				url: "get_save_url.php",
				type: "POST",
				dataType: "json",
				data: {ide: ide},
				success: function (result) {
					if (result["url"] != "none") {
						$("#url").val(result["url"]);
					}						
				},
				error: function (xhr, s, e) {
					alert(xhr.responseText);
				}
			});
			file = ''+ide+'.txt';
		}
		$(document).ready(function () {
			var url1 = $(".menu_adm_poll li:first a").attr("href");
			var url2 = $(".menu_adm_poll li:second a").attr("href");
			$("#my_polls").change(function () {
				var ide_st = ''+$(this).val();
				var ide_len = ide_st.length;
				$(".menu_adm_poll li:first a").attr("href", substitui(url1, ide_st, criar_enquete_ide, ide_len));
				$(".menu_adm_poll li:second a").attr("href", substitui(url2, ide_st, enquete_ide, ide_len));
				var ide = $(this).val();
				
			});
			$("input[name='text_color']").click(function () {
				$("#enviar").prop("disabled", "disabled");
				pb = this.value;
				$.ajax({
					url: "poll_text_color.php",
					type: "POST",
					dataType: "json",
					data: {
						ide: $("#idEnquete").val(),
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
				conditions = [];
				conditions['url'] = 'url';
				valid = validateForm(document.url_enquete, conditions);
				if (valid) {
					alert("ATEN\u00c7\u00c3O: Este endere\u00e7o que voc\u00ea forneceu deve ser o endere\u00e7o de uma das p\u00e1ginas do seu site ou blog nas quais esta enquete que voc\u00ea est\u00e1 baixando estar\u00e1 sendo exibida. Se depois de algum tempo for verificado que esta enquete n\u00e3o se encontra na p\u00e1gina fornecida, ela estar\u00e1 sujeita a ser exclu\u00edda da Web Enquetes. Voc\u00ea pode atualizar o endere\u00e7o fornecido clicando novamente em \"Baixar HTML desta enquete\".");
					$.ajax({
						url: "get_save_url.php",
						type: "POST",
						dataType: "json",
						data: {
							ide: $("#idEnquete").val(),
							url: $("#url").val()
						},
						success: function (result) {
							if (result["status"] == "Sucesso") {
								$("#download").attr("href", file);
								document.getElementById("download").click();
								document.getElementById("close").click();
							} else alert(result["status"]);	
						},
						error: function (xhr, s, e) {
							alert(xhr.responseText);
						}
					});			
				}
			});
		});
	</script>
    </div>
    </div>
</div>

<div class="bkg-enquetes-h">
<?php include "latest_polls.php"; ?>
</div>

<?php include "footer.php"; ?>

</html>
