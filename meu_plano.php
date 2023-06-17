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
	require_once "funcoes/funcoesDesign.php";
	class MeuPlano extends DesignFunctions {
		public $idu;
		public function __construct($idu, $con) {
			$this->idu = $idu;
			$this->con = $con;
		}
		public function my_plan_data() {
			global $service;
			global $service_data;
			$idu = $this->idu;
			$args = array();
			$args[0][0] = "Data de aquisi&ccedil;&atilde;o do plano";
			$args[0][1] = $this->std_date_create($service_data[0]['dt_aquisicao']);
			$votos_enquetes = $this->select("select e.enquete, count(v.dt_voto) as votos from enquete e inner join voto v on e.idEnquete = v.cd_enquete where e.cd_usuario = $idu group by e.idEnquete");
			for ($i = 0; array_key_exists($i, $votos_enquetes); $i++) {
				$args[$i+1][0] = "Quantidade de votos na enquete \"".$votos_enquetes[$i]["enquete"]."\"";
				$args[$i+1][1] = $votos_enquetes[$i]["votos"];
			}
			$num_votos = $service->get_num_votos();
			$args[$i+1][0] = "Quantidade total de votos nas suas enquetes";
			$args[$i+1][1] = $num_votos;
			$plano = '';
			$votes_free_months = $service::VOTES_FREE_MONTHS;
			if ($service_data[0]['gratis']) {
				$args[$i+2][0] = "Quantidade total de meses gratuitos";
				$args[$i+2][1] = $service_data[0]['meses_gratis'];
				$args[$i+3][0] = "Quantidade de votos restantes para se adquirir mais um m&ecirc;s gr&aacute;tis.";
				$args[$i+3][1] = $votes_free_months*$service_data[0]['meses_gratis']-$num_votos;
				$plano = "Plano gratuito: come&ccedil;a com um m&ecirc;s gratuito e voc&ecirc; ganha mais um m&ecirc;s gr&aacute;tis a cada $votes_free_months votos totais nas suas enquetes.";
			} else {
				$period = $service_data[0]['periodo_pagamento'];
				$plan_data = $service->paid_plan_data($period);
				$args[$i+2][0] = "Per&iacute;odo de pagamento";
				$args[$i+2][1] = $plan_data[3];
				$votos_restantes = 0;
				if ($plan_data[4] > 0) { //$plan_data[4] é a quantidade limite de votos para não se exibir anúncios, de acordo com o plano escolhido.
					$votos_restantes = $plan_data[4]-$num_votos;
					if ($votos_restantes > 0) {
						$args[$i+3][0] = "Quantidade de votos restantes para nossos an&uacute;ncios voltarem a ser exibidos.";
						$args[$i+3][1] = $votos_restantes;
					} else {
						$args[$i+3][0] = "Nossos an&uacute;ncios est&atilde;o sendo exibidos novamente, pois a quantidade de votos na sua enquete &eacute; ultrapassou ".$plan_data[4]." votos em ".(-$votos_restantes)." votos.";
						$args[$i+3][1] = " - ";
					}
				} else {
					$args[$i+3][0] = "Sua enquete n&atilde;o exibir&aacute; nossos an&uacute;ncios, indepentendemente da quantidade de votos que voc&ecirc; adquirir para sua enquetes.";
					$args[$i+3][1] = " - ";
				}
				$months_gone = $service->months_gone($service_data[0]['dt_aquisicao']);
				for ($j = 0; $j < $months_gone; $j += $period) {}
				$months_acq_date = strtotime($service_data[0]['dt_aquisicao'])/($service::MES*86400);
				$next_pay_date = date($this->dateformat, (int) (($months_acq_date+$j)*$service::MES*86400));
				$args[$i+4][0] = "Pr&oacute;xima data em que ser&aacute; cobrada sua assinatura para mais $period meses de benef&iacute;cios avan&ccedil;ados.";
				$args[$i+4][1] = $next_pay_date;
				$plano = $plan_data[5];
			}
			$this->write_table($args);
			echo "<p>$plano</p>";
		}
	}
	$design = new MeuPlano($we->idu, $we->con);
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