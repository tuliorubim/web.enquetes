<?php include "bd.php";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>
<body>
<form name="adminCliente" method="post" action="adminClientes.php">
<span style="width:400px;"><p><label for='email'>E-mail do Usu&aacute;rio: </label>
<input type="text" name="email" id="email" maxlength="128" size="50" value="<?php echo $_GET['email'];?>" /><br />
<input type="hidden" name="idCliente" id="idCliente" />
<input type="button" name="selecionar" id="selecionar" value="Selecionar" /><input type="button" name="novo" id="novo" value="Novo" /></p>
<p><label for='service'>Servi&ccedil;o: </label>
<select name="service" id="service" value="0" onchange="this.value = this.options[this.selectedIndex].value;">
	<option value="0">0</option>
	<option value="1">1</option>
</select></p>
<?php
$lim_votes1 = $service::LIM_VOTES_NO_ADS1;
$lim_votes2 = $service::LIM_VOTES_NO_ADS2;
$desconto_anual = $service::DESCONTO_ANUAL;
$desconto_sem = $service::DESCONTO_SEM;
$status = $service->status;
$we->write_status();
$args = array();
if ($we->idu == 55291 && isset($_GET['email'])) {
	$email = $_GET['email'];
	$sql = "select c.idCliente, c.usuario, c.data_cadastro, c.cd_servico, v.cd_enquete, count(v.dt_voto), e.dt_criacao from cliente c left join enquete e on c.idCliente = e.cd_usuario left join voto v on e.idEnquete = v.cd_enquete where c.usuario like '%$email%' group by c.idCliente, v.cd_enquete, e.dt_criacao order by c.idCliente desc, v.cd_enquete desc";
	$args = $we->select($sql);
	$we->select("select periodo_pagamento from aquisicao_servico where cd_usuario = ".$args[0][0], array("period"));
}
?>
<div id='select_plan'> 
<h3>Escolha o per&iacute;odo de cobran&ccedil;a</h3>
<h4>COBRAN&Ccedil;A ANUAL</h4>
<p><input type="radio" name="period" id="period12" value="12" <?php echo (!isset($period) || $period == 12) ? "checked='checked'" : "";?>>
<?php
echo htmlentities("A assinatura de cobrança anual elimina todos os nossos anúncios de suas enquetes e te dá $desconto_anual % de desconto no valor a ser pago por uma assinatura Web Enquetes.", ENT_NOQUOTES, 'ISO-8859-1', true); 
?></p>
<p><b>Valor: R$ <?php $plan_data = $service->paid_plan_data(12); echo $plan_data[1];?>/m&ecirc;s</b> igual a R$ <?php echo $plan_data[0];?>/ano</p>
<h4>COBRAN&Ccedil;A SEMESTRAL</h4>
<p><input type="radio" name="period" id="period6" value="6" <?php echo (isset($period) && $period == 6) ? "checked='checked'" : "";?>>
<?php
echo htmlentities("A assinatura de cobrança semestral elimina todos os nossos anúncios de suas enquetes e te dá $desconto_sem % de desconto no valor a ser pago por uma assinatura Web Enquetes.", ENT_NOQUOTES, 'ISO-8859-1', true); 
?></p>
<p><b>Valor: R$ <?php $plan_data = $service->paid_plan_data(6); echo $plan_data[1];?>/m&ecirc;s</b> igual a R$ <?php echo $plan_data[0];?>/semestre</p>
<h4>COBRAN&Ccedil;A TRIMESTRAL</h4>
<p><input type="radio" name="period" id="period3" value="3" <?php echo (isset($period) && $period == 3) ? "checked='checked'" : "";?>>
<?php
echo htmlentities("A assinatura de cobrança trimestral elimina todos os nossos anúncios de suas enquetes enquanto todas elas não atingem um total de $lim_votes2 respostas. Depois dessa marca, as propagandas voltam a ser exibidas nelas.", ENT_NOQUOTES, 'ISO-8859-1', true); 
?></p>
<p><b>Valor: R$ <?php $plan_data = $service->paid_plan_data(3); echo $plan_data[1];?>/m&ecirc;s</b> igual a R$ <?php echo $plan_data[0];?>/trimestre</p>
<h4>COBRAN&Ccedil;A MENSAL</h4>
<p><input type="radio" name="period" id="period1" value="1" <?php echo (isset($period) && $period == 1) ? "checked='checked'" : "";?>>
<?php
echo htmlentities("A assinatura de cobrança mensal elimina todos os nossos anúncios de suas enquetes enquanto todas elas não atingem um total de $lim_votes1 respostas. Depois dessa marca, as propagandas voltam a ser exibidas nelas.", ENT_NOQUOTES, 'ISO-8859-1', true); 
?></p>
<p><b>Valor: R$ <?php $plan_data = $service->paid_plan_data(1); echo $plan_data[1];?>/m&ecirc;s</b></p>
</div></span>
<span style='width:400px;'>
<p>
<?php
if (!empty($args)) {
	for ($i = 0; $args[$i][0] !== NULL; $i++) {
		echo " IdCliente: ".$args[$i][0].", E-mail: ".$args[$i][1].", Data de cadastro: ".$args[$i][2].", Enquete ".$args[$i][4].": ".$args[$i][5]." votos. Data da enquete: ".$args[$i][6]."<br>";
	}
?>
	<input type="hidden" name="idCliente" id="idCliente" value="<?php echo $args[0][0];?>"/>
	<input type="submit" name="salvar" value="Salvar" />
	<script language="javascript">
	$(function () {
		$("#idCliente").val("<?php echo $args[0][0];?>");
	});
	</script>
<?php	
}
?>
</p>
</span></form>
<div>
<?php
if (!empty($args)) {
?>
 	<script language="javascript">
	$(function () {
		$("#service").val("<?php echo $args[0][3];?>");
	});
	</script>
<?php	
}
?>
<script language="javascript">
	$(function () {
		$("#selecionar").click(function () {
			window.location.href = "formClientes.php?email="+$("#email").val();
		});
		$("#novo").click(function () {
			window.location.href = "formClientes.php";
		});
		$("#service").on("change", function () {
			if ($(this).val() == '0') {
				$("#select_plan").css("display", "none");
			} else $("#select_plan").css("display", "");
		});
		if ($("#service").val() == '0') {
			$("#select_plan").css("display", "none");
		} else $("#select_plan").css("display", "");
	});
</script>
</div>


</body>
</html>
