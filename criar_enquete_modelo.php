<?php
	include "bd.php";
	require_once "dados_webenquetes.php";
	Dados_webenquetes::setFormTabela1();
	Dados_webenquetes::setFormTabela2();
	Dados_webenquetes::setFormTabela3();
	Dados_webenquetes::setFormTabela4();
	$we->select("select cd_servico from cliente where idCliente = $we->idu", array('cd_servico'));
	Dados_webenquetes::setFormTabela5($cd_servico);
	
?>
