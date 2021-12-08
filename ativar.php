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
	<h2>ATIVA&Ccedil;&Atilde;O DA ENQUETE</h2>
	<p>
	Falta bem pouco para sua enquete estar dispon&iacute;vel para vota&ccedil;&atilde;o, bastando para isso fornecer um (alguns) dado(s) rapidamente.
	</p>
	<br>
	<form name="ativar" method="post">
	<p>
	Voc&ecirc; tem um site ou blog?
	<br>
	<input type="radio" name="possui_site" value="1"> Sim.<br>
	<input type="radio" name="possui_site" value="0"> N&atilde;o.<br>
	</p>
	<p id="hospedar">
	Voc&ecirc; vai hospedar sua enquete no seu site ou blog?
	<br>
	<input type="radio" name="vai_hospedar" value="1"> Sim.<br>
	<input type="radio" name="vai_hospedar" value="0"> N&atilde;o.<br>
	</p>
	<p id="pagina">
	Escreva abaixo a p&aacute;gina exata do seu site ou blog onde estar&aacute; sua enquete.<br>
	<input type="text" name="url" id="url" size="50" maxlength="1024"> 
	<input type="button" name="validar_url" value="Validar url">
	</p>
	<p id="finalizar">
	Finalize a ativa&ccedil;&atilde;o e saiba as instru&ccedil;&otilde;es para download do HTML da sua enquete.
	</p>
	<input type="submit" class="btn btn-primary estilo-modal2" name="finalizar" value="Finalizar Ativa&ccedil;&atilde;o"disabled></form>
	<script language="javascript">
	$(document).ready(function () {
		$("input[name='possui_site']").click(function () {
			if ($(this).val() == '0') {  
				$("input[name='finalizar']").removeAttr("disabled");
				$("#hospedar").fadeOut(400);
				$("#pagina").fadeOut(400);
				$("#finalizar").fadeOut(400);
			} else {
				$("input[name='finalizar']").prop("disabled", "disabled");	
				$("#hospedar").fadeIn(800);
			}
		});
		$("input[name='vai_hospedar']").click(function () {
			if ($(this).val() == '0') {  
				$("input[name='finalizar']").removeAttr("disabled");
				$("#pagina").fadeOut(400);
				$("#finalizar").fadeOut(400);
			} else {
				$("input[name='finalizar']").prop("disabled", "disabled");	
				$("#pagina").fadeIn(800);
			}
		});
		$("input[name='url']").keydown(function (){
			$("#finalizar").fadeOut(400);
			$("input[name='finalizar']").prop("disabled", "disabled");
		});
		c = '^(https?:\\/\\/)?(w{2,3}\\d\\.)?(([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+([a-z]{2,})(\\.[a-z]{2})?(\\/[-a-z\\d%_.~+]*)*(\\?[a-z\\d%_.;&~+=-]*)?(\\#[-a-z\\d_]*)?$'; 
		var regExp = new RegExp(c);
		$("input[name='validar_url']").click(function() {
			if (document.ativar.url.value.search(regExp) != -1) {
				$("#finalizar").fadeIn(400);
				$("input[name='finalizar']").removeAttr("disabled");
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