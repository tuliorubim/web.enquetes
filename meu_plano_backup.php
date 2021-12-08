<?php
session_start();
include "bd.php";
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
	$data = $service->get_acquired_service();
	$args = array();
	?>
	<p><label for='data1' class='plan_labels'>Data de aquisi&ccedil;&atilde;o do plano:</label>
	<?php 
	echo "<span id='data1'>".std_date_create($data[0]['dt_aquisicao'])."</span></p>";
	$votos_enquetes = select("select e.enquete, count(v.dt_voto) as votos from enquete e inner join voto v on e.idEnquete = v.cd_enquete where e.cd_usuario = $idu group by e.idEnquete");
	for ($i = 0; $votos_enquetes[$i]["enquete"] !== NULL; $i++) {
	?>
		<p><label for='data<?php echo ($i+2);?>' class='plan_labels'>Quantidade de votos na enquete "<?php echo $votos_enquetes[$i]["enquete"];?>":</label>
	<?php
		echo "<span id='data".($i+2)."'>".$votos_enquetes[$i]["votos"]."</span></p>";
	}
	$num_votos = $service->get_num_votos();
	?>
	<p><label for='data<?php echo ($i+2);?>' class='plan_labels'>Quantidadde total de votos nas suas enqeutes:</label>
	<?php
	echo "<span id='data".($i+2)."'>$num_votos</span></p>";
	$plano = '';
	$votes_free_months = $service::VOTES_FREE_MONTHS;
	if ($data[0]['gratis']) {
		$args[$i+2][0] = "Quantidade total de meses gratuitos";
		$args[$i+2][1] = $data[0]['meses_gratis'];
		$args[$i+3][0] = "Quantidade de votos restantes para se adquirir mais um m&ecirc;s gr&aacute;tis.";
		$args[$i+3][1] = $service::VOTES_FREE_MONTHS*$data[0]['meses_gratis']-$num_votos;
		$plano = "Plano gratuito: come&ccedil;a com um m&ecirc;s gratuito e voc&ecirc; ganha mais um m&ecirc;s gr&aacute;tis a cada $votes_free_months votos totais nas suas enquetes.";
	} elseif ($data) {
		$period = $data[0]['periodo_pagamento'];
		$plan_data = $service->paid_plan_data($period);
		$args[$i+2][0] = "Per&iacute;odo de pagamento";
		$args[$i+2][1] = $plan_data[3];
	 	$votos_restantes = 0;
		if ($plan_data[4] > 0) {
			$votos_restantes = $plan_data[4]-$num_votos;
			if ($votos_restantes > 0) {
				$args[$i+3][0] = "Quantidade de votos restantes para nossos an&uacute;ncios voltarem a ser exibidos.";
				$args[$i+3][1] = $votos_restantes;
			} else {
				$args[$i+3][0] = "Nossos an&uacute;ncios est&atilde; sendo exibidos novamente, pois a quantidade de votos na sua enquete &eacute; ultrapassou ".$plan_data[4]." votos em ".(-$votos_restantes)." votos.";
				$args[$i+3][1] = " - ";
			}
		} else {
			$args[$i+3][0] = "Sua enquete n&atilde;o exibir&aacute; nosso an&ucute;, indepentendemente da quantidade de votos que voc&ecirc; adquirir para sua enquetes.";
			$args[$i+3][1] = " - ";
		}
		$months_gone = $service->months_gone($data[0]['dt_aquisicao']);
		for ($i = 0; $i < $months_gone; $i += $period) {}
		$months_acq_date = strtotime($data[0]['dt_aquisicao'])/($service::MES*86400);
		$next_pay_date = date($dateformat, ($months_acq_date+$i)*$service::MES*86400);
		$args[$i+4][0] = "Pr&oacute;xima data em que ser&aacute; cobrada sua assinatura para mais $period meses de benef&iacute;cios avan&ccedil;ados.";
		$args[$i+4][1] = $next_pay_date;
		$plano = $plan_data[5];
	}
	//write_table($args);
	echo "<p>$plano</p>";
	$status = $service->get_free_months();
	if ($service->status == '') {
		$status = $service->change_status();
	}
	write_status('status2');
	?>
	</div>
	</div>
    </div>
</div>

<div class="bkg-enquetes-h">
<?php include "latest_polls.php"; ?>
</div>

<?php include "footer.php"; ?>

</html>