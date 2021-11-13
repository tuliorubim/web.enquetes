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
	<h1>Assintatura gratuita</h1>
	<?php
	$votes_free_monts = $service::VOTES_FREE_MONTHS;
	?>
	<p>
    <?php
	echo htmlentities("A assinatura para se adquirir gratuitamente benefícios avançados para criadores de enquetes só pode ser feita uma vez. Nessa assinatura, suas enquetes não deixarão de exibir nossos anúncios em nenhum momento, embora com ela você possa exibir o seu próprio anúncio nas suas enquetes. Para saber este e outros benefícios da assinatura, gratuita ou não, clique ", ENT_NOQUOTES, 'ISO-8859-1', true);
	echo "<a href='premium.php' target='_blank'>aqui</a>.";
	?>
	</p>
	<p>
	<?php
	echo htmlentities("Tornando-se assinante grátis, você ganha de início 30 dias de benefícios avançados gratuitos. Se suas enquetes forem bem votadas, você pode conseguir outros meses gratuitos da seguinte forma: a cada $votes_free_monts respostas que você conseguir no total das suas enquetes, você ganha mais um mês grátis, se você atingir essa marca de respostas antes de terminar o período gratuito total ao qual você tem direito. Por exemplo: se você atingir $votes_free_monts respostas nas suas enquetes, você ganha mais um mês gratuito além do primeiro mês grátis, totalizando dois meses gratuitos. Se antes de se completarem esses dois meses você conseguir mais $votes_free_monts respostas às suas enquetes, você ganha um terceiro mês gratuito. Agora, se até o final do terceiro mês grátis você não conseguir mais $votes_free_monts respostas, sua assinatura gratuita chegará ao fim, e não será possível renová-la. Daí pra frente, só será possível adquirir os benefícios avançados por meio de uma assinatura paga, a qual oferece a vantagem de se eliminar propagandas das suas enquetes, de acordo com o período de pagamento da mesma.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<?php
	$we->select("select usuario from cliente where idCliente = $we->idu", array("user2"));
	if (!empty($user2)) {
		if (empty($service_data)) {
	?>
		<form name="free_plan" method="post" action="get_free_plan.php" style="float:left; margin-right:10px; margin-top:10px;">
			<input type="hidden" name="free_acqr">
			<input type="button" name="gratis" value="ADQUIRIR ASSINATURA GR&Aacute;TIS">
		</form>
		<script language="javascript"> 
		$(function () {
			$("input[name='gratis']").click(function () {
				//if (confirm("Ao confirmar esta assinatura, ela come\u00e7a a valer e n\u00e3o \u00e9 poss\u00edvel paus\u00e1-la e nem suspend\u00ea-la. Tamb\u00e9m n\u00e3o \u00e9 poss\u00edvel recuper\u00e1-la caso voc\u00ea a substitua por uma assinatura paga. Deseja confirmar a aquisi\u00e7\u00e3o desta assinatura agora?")) {
					document.free_plan.free_acqr.value = "sim";
					document.free_plan.submit();
				//}
			});
		}); 
		</script>
		<?php } ?>
		<form name="paid_plan" method="post" action="assinar.php">
			<input type="submit" name="no_ads" value="ASSINATURA SEM AN&Uacute;NCIOS" class="btn btn-primary estilo-modal">
		</form>
	<?php 
	} else { 
		echo "<p>";
		echo htmlentities("Crie sua enquete, começando-a pelo formulário ao lado. Depois disso, considere adquirir esta assinatura gratuita, se você ainda não a adquiriu. ", ENT_NOQUOTES, 'ISO-8859-1', true);
		echo "</p>";
	}
	?>
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