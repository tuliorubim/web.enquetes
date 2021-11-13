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
    <h4>Envie-nos sugest&otilde;es, d&uacute;vidas ou reclama&ccedil;&otilde;es</h4>
	<br><br>
    <form name="sendmail" method="post" action="contato_controle.php">
	<div class="form-group">
    <input type="text" name="name" class="form-control input-lg" id="exampleInputEmail1" placeholder="Seu Nome">
    </div>
    <div class="form-group">
    <input type="text" name="email" class="form-control input-lg" id="exampleInputEmail1" placeholder="Seu E-mail">
    </div>
    <div class="form-group">
    <input type="text" name="subject" class="form-control input-lg" id="exampleInputEmail1" placeholder="Assunto">
    </div>
    <div class="form-group">
    <textarea rows="4" name="message" class="form-control input-lg" id="exampleInputEmail1" placeholder="Mensagem"></textarea>
    </div>
   <!-- Button -->
    <input type="hidden" name="send">
	<input type="button" id="send2" class="btn btn-primary estilo-modal" value="ENVIAR">
	<script language="javascript">
	$(document).ready(function () {
		$("#send2").click(function () {
			var conditions = [];
			conditions['email'] = 'email';
			conditions['message'] = 1;
			valid = validateForm(document.sendmail, conditions);
			if (valid) {
				document.sendmail.send.value = "ENVIAR";
				document.sendmail.submit();
			}
		});
	});
	</script>
    </form>
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