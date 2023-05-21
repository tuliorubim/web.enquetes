<?php
class Service extends DBFunctions{
	private $cd_usuario;
	public $status;
	const VOTES_FREE_MONTHS = 1000;
	const LIM_VOTES_NO_ADS1 = 300;
	const LIM_VOTES_NO_ADS2 = 1000;
	const MES = 365.25/12;
	const PRICE = 39.9;
	const DESCONTO_SEM = 10;
	const DESCONTO_ANUAL = 25;
	
	public function __construct($cd_usuario) {
		$this->cd_usuario = $cd_usuario;
	}
	public function get_user() {
		return $this->cd_usuario;
	}
	public function get_status() {
		return $this->status;
	}
	public function get_num_votos() {
		$args = $this->select("select count(v.dt_voto) from cliente c inner join enquete e on c.idCliente = e.cd_usuario inner join voto v on e.idEnquete = v.cd_enquete where c.idCliente = $this->cd_usuario");
		return (!empty($args)) ? $args[0][0] : 0;
	}
	public function paid_plan_data($periodo_pagamento) {
		$this->status = '';
		$days_left = 0;
		$price = '';
		$month_price = '';
		$period = '';
		$lim_votes_no_ads = 0;
		$desc = '';
		switch ($periodo_pagamento) {
			case 12: 
				$this->status = "Voc&ecirc; adquiriu o plano anual e suas enquetes n&atilde;o exibir&atilde;o nossos an&uacute;ncios.";
				$days_left = 30;
				$p = self::PRICE*(1-self::DESCONTO_ANUAL/100);
				$month_price = number_format($p, 2, ',', '.');
				$price = number_format($p*$periodo_pagamento, 2, ',', '.');
				$period = "Anual";
				$desc = "Plano anual: sem nossos an&uacute;ncios e com cobran&ccedil;a anual.";
				break;
			case 6: 
				$this->status = "Voc&ecirc; adquiriu o plano semestral e suas enquetes n&atilde;o exibir&atilde;o nossos an&uacute;ncios.";
				$days_left = 20;
				$p = self::PRICE*(1-self::DESCONTO_SEM/100);
				$month_price = number_format($p, 2, ',', '.');
				$price = number_format($p*$periodo_pagamento, 2, ',', '.');
				$period = "Semestral";
				$desc = "Plano semestral: sem nossos an&uacute;ncios e com cobran&ccedil;a semestral.";
				break;
			case 3: 
				$this->status = "Voc&ecirc; adquiriu o plano trimestral e suas enquetes n&atilde;o exibir&atilde;o nossos an&uacute;ncios se n&atilde;o atingirem um total de self::LIM_VOTES_NO_ADS2 votos. Passando disso, os an&uacute;ncios voltam a ser exibidos nas suas enquetes.";
				$days_left = 10;
				$p = self::PRICE;
				$month_price = number_format($p, 2, ',', '.');
				$price = number_format($p*$periodo_pagamento, 2, ',', '.');
				$period = "Trimestral";
				$lim_votes_no_ads = self::LIM_VOTES_NO_ADS2;
				$desc = "Plano trimestral: cobran&ccedil;a trimestral e sem nossos an&uacute;ncios antes de suas enquetes receberem um total de $lim_votes_no_ads votos.";
				break;
			case 1: 
				$this->status = "Voc&ecirc; adquiriu o plano trimestral e suas enquetes n&atilde;o exibir&atilde;o nossos an&uacute;ncios se n&atilde;o atingirem um total de self::LIM_VOTES_NO_ADS1 votos. Passando disso, os an&uacute;ncios voltam a ser exibidos nas suas enquetes.";
				$days_left = 6;
				$p = self::PRICE;
				$month_price = number_format($p, 2, ',', '.');
				$price = number_format($p*$periodo_pagamento, 2, ',', '.');
				$period = "Mensal";
				$lim_votes_no_ads = self::LIM_VOTES_NO_ADS1;
				$desc = "Plano mensal: cobran&ccedil;a mensal e sem nossos an&uacute;ncios antes de suas enquetes receberem um total de $lim_votes_no_ads votos.";
				break;
		}
		return array($price, $month_price, $days_left, $period, $lim_votes_no_ads, $desc);
	}
	public function get_acquired_service() {
		$args = $this->select("select * from aquisicao_servico where cd_usuario = $this->cd_usuario");
		return $args;
	}
	public function months_gone($date) {
		return (strtotime(date("Y-m-d H:i:s"))-strtotime($date))/(self::MES*86400);
	}
	public function acquire_premium_service ($periodo_pagamento=NULL, $email='') {
		$con = $this->con;
		$this->status = '';
		$cdu = $this->cd_usuario;	
		$args = $this->get_acquired_service();
		mysqli_query($con, "update cliente set cd_servico = 1 where idCliente = $cdu");
		$date = date("Y-m-d H:i:s");
		if ($periodo_pagamento === NULL) {
			$num_votos = $this->get_num_votos();
			$meses_gratis = ceil($num_votos/self::VOTES_FREE_MONTHS);
			if ($meses_gratis == 0) $meses_gratis = 1;
			mysqli_query($con, "insert into aquisicao_servico (cd_usuario, cd_servico, meses_gratis, dt_aquisicao) values ($cdu, 1, $meses_gratis, '$date')");
			$meses = ($meses_gratis == 1) ? "m&ecirc;s" : "meses";
			$this->status = "Voc&ecirc; acaba de adquirir os benef&iacute;cios avan&ccedil;ados da Web Enquetes gratuitamente. No momento voc&ecirc; tem $meses_gratis $meses gr&aacute;tis.";
		} else {
			$paid_plan_data = $this->paid_plan_data($periodo_pagamento);
			if (empty($args)) {
				mysqli_query($con, "insert into aquisicao_servico (cd_usuario, cd_servico, dt_aquisicao, gratis, periodo_pagamento, billings, pay_email) values ($cdu, 1, '$date', 0, $periodo_pagamento, 1, '$email')");
			} else {
				mysqli_query($con, "update aquisicao_servico set gratis = 0, em_vigor = 1, periodo_pagamento = $periodo_pagamento, dt_aquisicao = '$date', billings = 1, pay_email = '$email' where cd_usuario = $cdu");
			}
			$this->status = "O cliente $cdu acabou de adquirir a assinatura paga ".$paid_plan_data[3].".";
		}
	}
	public function cancel_premium_service() {
		$con = $this->con;
		$this->status = '';
		$cdu = $this->cd_usuario;	
		$args = $this->get_acquired_service();
		if (!empty($args)) {
			mysqli_query($con, "update aquisicao_servico set em_vigor = 0 where cd_usuario = $cdu");
			$this->status = "O cliente $cdu n&atilde;o &eacute; mais assinante.";
		}
	}
	public function get_free_months () {
		$con = $this->con;
		$this->status = '';
		$cdu = $this->cd_usuario;	
		$args = $this->get_acquired_service();
		if (!empty($args) && $args[0]['em_vigor']) {
			if ($args[0]['gratis']) {
				$num_votes = $this->get_num_votos();
				$free_months = $num_votes/self::VOTES_FREE_MONTHS+1;
				$months_gone = $this->months_gone($args[0]['dt_aquisicao']);																											
				if (floor($free_months) > floor($months_gone)) {
					$free_months = floor($free_months);
					if ($free_months > $args[0]['meses_gratis']) {
						mysqli_query($con, "update aquisicao_servico set meses_gratis = $free_months where cd_usuario = $cdu");	
						$this->status = "Parab&eacute;ns! Voc&ecirc; acaba de ganhar mais ".($free_months - $args[0]['meses_gratis'])." meses gratuito porque suas enquetes acabam de atingir $num_votes respostas antes de terminarem ".$args[0]['meses_gratis']." meses desde que voc&ecirc; adquiriu gratuitamente os benef&iacute;cios destinados a assinantes. Aproveite!";
					}
				} elseif (round(self::MES*($months_gone-$args[0]['meses_gratis'])) > 10) {
					mysqli_query($con, "update aquisicao_servico set em_vigor = 0 where cd_usuario = $cdu");
					mysqli_query($con, "update cliente set cd_servico = 0 where idCliente = $cdu");	
				}
			}
		} 
		return $this->status;
	}
	public function change_status() {
		$con = $this->con;
		global $cds;
		$status = '';
		$cdu = $this->cd_usuario;	
		$args = $this->get_acquired_service();
		$status = '';
		if (!empty($args)) {
			$acq_date = $args[0]['dt_aquisicao'];
			$billings = $args[0]["billings"];
			$period = $args[0]["periodo_pagamento"];
			$months_gone = $this->months_gone($acq_date);
			$days_gone = (int) (self::MES*$months_gone);
			$daily_period = self::MES*$period;
			//$total_days_left = $daily_period-$days_gone%$daily_period;
			$total_days_left = round((strtotime($acq_date)-strtotime(date("Y-m-d H:i:s")))/86400+$daily_period*$billings);
			if ($args[0]['em_vigor']) {
				if ($args[0]['gratis']) {
					$free_months = $args[0]['meses_gratis'];
					$months_left = $free_months-$months_gone;
					$total_days_left = floor($months_left*self::MES);
					if ($total_days_left > 10) {
						$int_months_left = floor($months_left);
						$days_left = floor(($months_left-$int_months_left)*self::MES);
						$and_n_days = ($days_left > 0) ? " e $days_left dias" : '';
						$status = "Voc&ecirc; ainda tem $int_months_left meses$and_n_days de servi&ccedil;os avan&ccedil;ados gr&aacute;tis.";
					} elseif ($total_days_left <= 10 && $total_days_left > 0) {
							$status = "ATEN&Ccedil;&Atilde;O: faltam apenas $total_days_left dias para sua assinatura gratuita se esgotar. Para continuar tendo os benef&iacute;cios de assinante, incluindo o de ESCONDER RESULTADOS PARCIAIS, adquira uma assinatura paga, a menos que voc&ecirc; esteja prestes a conseguir mais um m&ecirc;s gr&aacute;tis.";
					} elseif ($total_days_left >= -10) {
						$days_gone = -$total_days_left;
						$days_left2 = 10 - $days_gone;
						$status = "Seu plano gratuito terminou h&aacute; $days_gone dias. Dentro de $days_left2 dias, voc&ecirc; perder&aacute; seus benef&iacute;cios de assinante, inclusive o de ESCONDER RESULTADOS PARCIAIS, caso n&atilde;o renove sua assinatura com um plano pago.";
					}	
				} else {
					$num_votes = $this->get_num_votos();
					$paid_plan_data = $this->paid_plan_data($period);
					if ($period == 3 && $num_votes > self::LIM_VOTES_NO_ADS2) {
						$limit2 = self::LIM_VOTES_NO_ADS2;
						$status = "Suas enquetes agora exibem nossos an&uacute;ncios porque elas receberam mais de $limit2 respostas no total.";
					} elseif ($period == 1 && $num_votes > self::LIM_VOTES_NO_ADS1) {
						$limit1 = self::LIM_VOTES_NO_ADS1;
						$status = "Suas enquetes agora exibem nossos an&uacute;ncios porque elas receberam mais de $limit1 respostas no total.";
					}
					if ($total_days_left <= $paid_plan_data[2] && $total_days_left > 0) {
						$status = "Dentro de $total_days_left dias, ser&aacute; cobrado automaticamente R$ ".$paid_plan_data[0]." referente a mais $period meses de assinatura.";
					} elseif ($total_days_left <= 0) {
						$billings += 1;
						mysqli_query($con, "update aquisicao_servico set billings = $billings where idAquisicao = ".$args[0]["idAquisicao"]);
						$status = "Foi cobrado hoje o valor de R$ ".$paid_plan_data[0]." referente a mais $period meses de assinatura.";
					}
				}
			} elseif ($args[0]['gratis']) {
				$status = "A vig&ecirc;ncia do seu plano gratuito terminou. Se voc&ecirc; escondeu resultados parciais, eles est&atilde;o vis&iacute;veis publicamente, agora, e suas enquetes n&atilde;o podem ser exlu&iacute;das. Mas voc&ecirc; pode voltar a ter os benef&iacute;cios de assinante adquirindo um plano pago clicando <a href='assinar.php'>aqui.</a>";
			} elseif ($total_days_left > 0) {
				$status = "Voc&ecirc; cancelou sua assintatura, e voc&ecirc; tem ainda $total_days_left dias de benef&iacute;cios de assinante, inclusive o de ESCONDER RESULTADOS PARCIAIS."; 
				if ($total_days_left < 6) $status .= " Caso queira reativar sua assinatura, clique <a href='assinar.php'>aqui</a>.";
			} else {
				$this->select("select cd_servico from cliente where idCliente = $cdu", array("cds"));
				if ($cds > 0)
					mysqli_query($con, "update cliente set cd_servico = 0 where idCliente = $cdu");
				$status = "A vig&ecirc;ncia do seu plano terminou. Se voc&ecirc; escondeu resultados parciais, eles est&atilde;o vis&iacute;veis publicamente, agora, e suas enquetes n&atilde;o podem ser exlu&iacute;das. Mas voc&ecirc; pode voltar a ter os benef&iacute;cios de assinante reativando sua assinatura <a href='assinar.php'>aqui.</a>";
			}	
		}
		$this->status = $status;
		return $this->status;
	}
}
?>
