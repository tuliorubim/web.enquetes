<?php
include "bd.php";

Dados_webenquetes::setFormTabela1();
$we->select("select cd_servico from cliente where idCliente = $we->idu", array('cd_servico'));
Dados_webenquetes::setFormTabela5($cd_servico);
Dados_webenquetes::setFormTabela7();
Dados_webenquetes::setFormTabela8();
Dados_webenquetes::setFormTabela9();
Dados_webenquetes::setFormTabela10();
Dados_webenquetes::setFormTabela11();
?>