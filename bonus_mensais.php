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
	echo htmlentities("A assinatura para se adquirir gratuitamente benef�cios avan�ados para criadores de enquetes s� pode ser feita uma vez. Nessa assinatura, suas enquetes n�o deixar�o de exibir nossos an�ncios em nenhum momento, embora com ela voc� possa exibir o seu pr�prio an�ncio nas suas enquetes. Para saber este e outros benef�cios da assinatura, gratuita ou n�o, clique ", ENT_NOQUOTES, 'ISO-8859-1', true);
	echo "<a href='premium.php' target='_blank'>aqui</a>.";
	?>
	</p>
	<p>
	<?php
	echo htmlentities("Tornando-se assinante gr�tis, voc� ganha de in�cio 30 dias de benef�cios avan�ados gratuitos. Se suas enquetes forem bem votadas, voc� pode conseguir outros meses gratuitos da seguinte forma: a cada $votes_free_monts respostas que voc� conseguir no total das suas enquetes, voc� ganha mais um m�s gr�tis, se voc� atingir essa marca de respostas antes de terminar o per�odo gratuito total ao qual voc� tem direito. Por exemplo: se voc� atingir $votes_free_monts respostas nas suas enquetes, voc� ganha mais um m�s gratuito al�m do primeiro m�s gr�tis, totalizando dois meses gratuitos. Se antes de se completarem esses dois meses voc� conseguir mais $votes_free_monts respostas �s suas enquetes, voc� ganha um terceiro m�s gratuito. Agora, se at� o final do terceiro m�s gr�tis voc� n�o conseguir mais $votes_free_monts respostas, sua assinatura gratuita chegar� ao fim, e n�o ser� poss�vel renov�-la. Da� pra frente, s� ser� poss�vel adquirir os benef�cios avan�ados por meio de uma assinatura paga, a qual oferece a vantagem de se eliminar propagandas das suas enquetes, de acordo com o per�odo de pagamento da mesma.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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
		echo htmlentities("Crie sua enquete, come�ando-a pelo formul�rio ao lado. Depois disso, considere adquirir esta assinatura gratuita, se voc� ainda n�o a adquiriu. ", ENT_NOQUOTES, 'ISO-8859-1', true);
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