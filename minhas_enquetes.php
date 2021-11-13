<?php
include "bd.php";
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
    <div class="col-md-6">
    <h2>MINHAS ENQUETES</h2>
    <?php
	$status = $service->get_free_months();
	if ($service->status == '') {
		$status = $service->change_status();
	}
	if ($status != '') {
		echo "<p><span class='status2'>$status</span></p>";
	}
	require_once 'create_html.php';
	$design = new Create_HTML();
	$design->idu = $we->idu;
	$design->con = $we->con;
	$design->minhas_enquetes();
	?>
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
