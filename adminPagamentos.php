<?php
function adminPagamentos () {
	$args = array();
	$args = select("select c.idCliente, c.usuario, a.idAquisicao, a.cd_servico, a.dt_aquisicao, p.idPagamento, p.dt_pagamento from cliente c inner join aquisicao_servico a on c.idCliente = a.cd_usuario left join pagamento p on a.idAquisicao = p.cd_aquisicao where c.cd_servico > 0");
	$now = date("Y-m-d H:i:s");
	$year_now = (int) substr($now, 0, 4);
	$month_now = (int) substr($now, 5, 2);
	$day_now = (int) substr($now, 8, 2);
	for ($i = 0; $args[$i][0] !== NULL; $i++) {
		$dt_aq = $args[$i][4];
		$year_aq = (int) substr($dt_aq, 0, 4);
		$month_aq = (int) substr($dt_aq, 5, 2);
		$day_aq = (int) substr($dt_aq, 8, 2);
		while ($args[$i][2] === $args[$i+1][2]) $i++;
		if (strtotime($args[$i][6]) <= 10800) {
			$dif = $now-$days_aq;
			select("select max(dt_envio) from enviados where cd_usuario = ".$args[$i][0], array("dt_envio"));
			$days_sent = strtotime($dt_envio)/86400;
			$dif2 = $now-$days_sent;
			if ($dif === 10) {
				
			}
		}
	}
}
?>
