<form name="start_survey" method="post" action="criar_enquete.php">
<h4 class="titu-cadastro">Comece sua enquete</h4>
<div class="form-group">
<input type="text" name="enquete" class="form-control input-lg" id="enquete" maxlength="64" placeholder="T&iacute;tulo da enquete">
</div>
<div class="form-group">
<select class="form-control input-lg" name="cd_categoria" onChange="this.value = this.options[this.selectedIndex].value">
<?php
$we->listar_categorias();
?>	
</select>
</div>
<div class="form-group">
<textarea rows="4" name="introducao" class="form-control input-lg" id="introducao" maxlength="1024" placeholder="Introdu&ccedil;&atilde;o (opcional)"></textarea>
</div>
<input type="hidden" name="button">
<div class="form-group">
<?php if (!$we->logged_in) { ?>
	<fb:login-button scope="public_profile,email" onlogin="checkLoginState(0);">Entre pelo FB e continue</fb:login-button>
<?php } else { ?>
<script language="javascript">
$(document).ready(function () {
	$("#singlebutton").click(function () {
		valid = false;
		conditions = [];
		conditions['enquete'] = 1;
		valid = validateForm(document.start_survey, conditions);
		if (valid) {
			document.start_survey.button.value = "ENVIAR";
			document.start_survey.submit();
		}
	});
});
</script>
  <label class="col-md-12 control-label" for="singlebutton"></label>
  <div class="col-md-12">
	<input type="button" id="singlebutton" name="singlebutton" class="btn btn-success btn-lg col-md-12" value="ENVIAR">
  </div>
<?php } ?>
</div>
</form>
