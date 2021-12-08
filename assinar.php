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
	<h1>Assintatura total ou parcialmente sem an&uacute;ncios</h1>
	<?php
	$lim_votes1 = $service::LIM_VOTES_NO_ADS1;
	$lim_votes2 = $service::LIM_VOTES_NO_ADS2;
	$desconto_anual = $service::DESCONTO_ANUAL;
	$desconto_sem = $service::DESCONTO_SEM;
	$status = $service->status;
	$we->write_status();
	?> 
	<h3>Escolha o per&iacute;odo de cobran&ccedil;a</h3>
	<form name="no_ads_plan" method="post">
	<h4>COBRAN&Ccedil;A ANUAL</h4>
	<p><input type="radio" name="period" id="period12" value="12" checked="checked">
	<?php
	echo htmlentities("A assinatura de cobrança anual elimina todos os nossos anúncios de suas enquetes e te dá $desconto_anual % de desconto no valor a ser pago por uma assinatura Web Enquetes.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p><b>Valor: R$ <?php $plan_data = $service->paid_plan_data(12); echo $plan_data[1];?>/m&ecirc;s</b> igual a R$ <?php echo $plan_data[0];?>/ano</p>
	<h4>COBRAN&Ccedil;A SEMESTRAL</h4>
	<p><input type="radio" name="period" id="period6" value="6">
	<?php
	echo htmlentities("A assinatura de cobrança semestral elimina todos os nossos anúncios de suas enquetes e te dá $desconto_sem % de desconto no valor a ser pago por uma assinatura Web Enquetes.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p><b>Valor: R$ <?php $plan_data = $service->paid_plan_data(6); echo $plan_data[1];?>/m&ecirc;s</b> igual a R$ <?php echo $plan_data[0];?>/semestre</p>
	<h4>COBRAN&Ccedil;A TRIMESTRAL</h4>
	<p><input type="radio" name="period" id="period3" value="3">
	<?php
	echo htmlentities("A assinatura de cobrança trimestral elimina todos os nossos anúncios de suas enquetes enquanto todas elas não atingem um total de $lim_votes2 respostas. Depois dessa marca, as propagandas voltam a ser exibidas nelas.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p><b>Valor: R$ <?php $plan_data = $service->paid_plan_data(3); echo $plan_data[1];?>/m&ecirc;s</b> igual a R$ <?php echo $plan_data[0];?>/trimestre</p>
	<h4>COBRAN&Ccedil;A MENSAL</h4>
	<p><input type="radio" name="period" id="period1" value="1">
	<?php
	echo htmlentities("A assinatura de cobrança mensal elimina todos os nossos anúncios de suas enquetes enquanto todas elas não atingem um total de $lim_votes1 respostas. Depois dessa marca, as propagandas voltam a ser exibidas nelas.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p><b>Valor: R$ <?php $plan_data = $service->paid_plan_data(1); echo $plan_data[1];?>/m&ecirc;s</b></p>
	</form>
	<p>Assinatua paga est&aacute; desabilitada por tempo indetermindado.</p>
	<?php
	if ($service_data[0]['gratis'] || !$service_data[0]['em_vigor']) {
	?>
		<p><div id="link12">12<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="BCVUBPXBCKRV8">
<input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - A maneira fácil e segura de enviar pagamentos online!">
<img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
<div id="link6">6<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="VTA67CPM59VQJ">
<input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - A maneira fácil e segura de enviar pagamentos online!">
<img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
<div id="link3">3<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="SHW2DU4QCMF3Q">
<input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - A maneira fácil e segura de enviar pagamentos online!">
<img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
<div id="link1">1<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="AJ7Q462X46566">
<input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - A maneira fácil e segura de enviar pagamentos online!">
<img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
</p>
		<script language="javascript">
		$(function () {
			document.getElementById('link12').style.display = 'none';
			document.getElementById('link6').style.display = 'none';
			document.getElementById('link3').style.display = 'none';
			document.getElementById('link1').style.display = 'none';
			/*$("input[name='period']").click(function () {
				var periods = [12, 6, 3, 1];
				for (var i = 0; i < 4; i++) { 
					document.getElementById('link'+periods[i]).style.display = (periods[i] == $(this).val()) ? '' : 'none';
					if (periods[i] != $(this).val())
						$('#link'+periods[i]).css("display", "none");
					else {
						$('#link'+periods[i]).css("display", "");
						//alert(periods[i]);
					}
				}
			});*/
		});
		</script>
	<?php } 
	if (empty($service_data)) {
	?>
		<form name="free_plan" method="post" action="bonus_mensais.php">
			<input type="submit" name="gratis" value="ASSINATURA GR&Aacute;TIS" style="margin-top:10px;">
		</form>
	<?php }	?>
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

