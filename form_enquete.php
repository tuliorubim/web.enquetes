<form name="cadastro" method="post" action="criar_enquete.php">
<h4 class="titu-cadastro">Comece sua enquete</h4>
<div class="form-group">
<p style="color:#FFFFFF"><input type="radio" name="content" value="poll" checked="checked" /> Somente question&aacute;rio</p>
<p style="color:#FFFFFF"><input type="radio" name="content" value="content" /> Texto/audio (e question&aacute;rio(s))</p>
</div>
<?php if (!$we->logged_in) { ?>
<div class="form-group">
<input type="text" name="usuario" class="form-control input-lg" id="usuario" maxlength="256" placeholder="E-mail (cadastrar e/ou fazer login)">
</div>
<div class="form-group">
<input type="password" name="senha" class="form-control input-lg" id="senha" maxlength="12" placeholder="Senha">
</div>
<?php } else { ?>
<div class="form-group">
<input type="text" name="title" class="form-control input-lg" id="enquete" maxlength="64" placeholder="T&iacute;tulo">
</div>
<div class="form-group">
<select class="form-control input-lg" name="cd_categoria" onChange="this.value = this.options[this.selectedIndex].value">
<?php
$we->listar_categorias();
?>	
</select>
</div>
<?php } ?>
<input type="hidden" name="button">
<script language="javascript">
$(document).ready(function () {
	$("input[name='content']").click(function () {
		//alert($(this).val());
		if ($(this).val() == "poll")
			$("form[name='cadastro']").attr("action", "criar_enquete.php");
		else if ($(this).val() == "content")
			$("form[name='cadastro']").attr("action", "criar_conteudo.php");	
	});
	$("#singlebutton").click(function () {
		valid = false;
		conditions = [];
		if (document.cadastro.usuario) {
			conditions['usuario'] = 'email';
			conditions['senha'] = 7;
		} 
		else {
			conditions['title'] = 1;
		}
		valid = validateForm(document.cadastro, conditions);
		if (valid) {
			document.cadastro.button.value = "ENVIAR";
			document.cadastro.submit();
		}
	});
});
</script>
<div class="form-group">
  <label class="col-md-12 control-label" for="singlebutton"></label>
  <div class="col-md-12">
	<input type="button" id="singlebutton" name="singlebutton" class="btn btn-success btn-lg col-md-12" value="ENVIAR">
  </div>
</div>
</form>
