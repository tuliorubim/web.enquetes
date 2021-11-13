<?php
include "bd.php";
require_once "funcoes/funcoesDesign.php";
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
	<h2>Meu Plano</h2>
	<div id="meu_plano">
	<?php
	require_once "create_html.php";
	$design = new Create_HTML();
	$design->con = $we->con;
	$design->idu = $we->idu;
	$design->my_plan_data();
	
	$status = $service->get_free_months();
	if ($service->status == '') {
		$status = $service->change_status();
	}
	if ($status != '') {
		echo "<p><span class='status2'>Status do plano: $status</span></p>";
	}
	if ($service_data[0]['gratis']) {
	?>
		<p><a href="assinar.php" class="btn btn-primary estilo-modal">Adquirir assinatura paga</a></p>
	<?php } else { ?>
		<A HREF="https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=JKWYESVD3TUBY">
<IMG SRC="https://www.paypalobjects.com/pt_BR/i/btn/btn_unsubscribe_LG.gif" BORDER="0">
</A>
		<script language="javascript">
		$(function () {
			$("#cancelar").click(function () {
				alert("Voc\u00ea s\u00f3 pode cancelar sua assinatura por meio do aplicativo PicPay, onde voc\u00ea fez sua assinatura.");
			});
		});
		</script>
	<?php } ?>
	</div>
	</div>
    </div>
</div>

<div class="bkg-enquetes-h">
<?php include "latest_polls.php"; ?>
</div>
<script language="javascript">
$(function () {
	$("#meu_plano table td:even").css("font-weight", "700");
});
</script>
<?php include "footer.php"; ?>

</html>