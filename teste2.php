<?php
include "funcoes/funcoesArquivo.php";
ini_set('memory_limit', '-1');
$queries = open_file("webenque_enquetes4.sql");
//cho substr($queries, 0, 1666);
$content = explode("Estrutura da tabela `", $queries);
$i = 0;
foreach ($content as $c) {
	$l = strpos($c, '`');
	$name = ($l > 0 && $l < 16) ? substr($c, 0, $l) : "c$i";
	save_file ($c, $name.".sql");
	$i++;
}

/*$queries1 = substr($queries, 0, $pos).substr($queries, $pos+$cont);
$pos = strpos($queries1,"Estrutura da tabela `poll_html");
$cont = strpos($queries1,"Estrutura da tabela `resposta")-$pos;
$queries2 = substr($queries1, 0, $pos).substr($queries1, $pos+$cont);
var_dump ($pos);
save_file ($queries2, "webenque_enquetes_4.sql");*/
echo "fim";
?>
