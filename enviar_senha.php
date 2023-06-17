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
    
	<form name="pesquisa" method="post" action="enviar_senha_confirm.php">
	<p><label for="textfield2">Digite seu e-mail de cadastro, clique Enviar e sua senha ser&aacute; enviada ao mesmo. Sua mensagem pode chegar na sua caixa de spam: </label>
	  <input type="text" name="email" class="label_pesquisa" id="textfield2" value=''>
	</p>
	<p>
	  <input type="button" class="btn btn-primary estilo-modal" id="button2" value="Enviar">
	  <input type="hidden" name="send">
	</p>
	</form>
	<script language="javascript">
	var conditions = [];
	conditions["email"] = "email";
	$("#button2").click( function () {
		valid = validateForm (document.pesquisa, conditions);
		if (valid) {
			document.pesquisa.send.value = "Enviar";
			document.pesquisa.submit();
		} 
	});
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