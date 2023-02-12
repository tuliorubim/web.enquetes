<?php
	include "bd.php";
	require_once "dados_webenquetes.php";
	Data_webenquetes::setFormTabela1();
	Data_webenquetes::setFormTabela2();
	Data_webenquetes::setFormTabela3();
	Data_webenquetes::setFormTabela4();
	$we->select("select cd_servico from cliente where idCliente = $we->idu", array('cd_servico'));
	Data_webenquetes::setFormTabela5($cd_servico);
	
?>
