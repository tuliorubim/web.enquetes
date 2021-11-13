<?php
include "bd.php";
$args = select("select idCliente from cliente");
echo date("Y-m-d H:i:s", time()).'<br>';
echo date("Y-m-d H:i:s", time()+1);
for ($i = 0; $i < 500; $i++) {
	$r = rand(815, 822);
	$u = $args[$i][0];
	$d = date("Y-m-d H:i:s", time()+$i);
	mysqli_query($con, "insert into voto (cd_usuario, cd_enquete, cd_pergunta, cd_resposta, dt_voto) values ($u, 247, 190, $r, '$d')"); 
}/*
for ($i = 0; $i < 80; $i++) {
	$r = rand(840, 841);
	$u = $args[$i][0];
	$d = date("Y-m-d H:i:s");
	mysqli_query($con, "insert into voto (cd_usuario, cd_enquete, cd_pergunta, cd_resposta, dt_voto) values ($u, 255, 195, $r, '$d')");
	sleep(1); 
	echo "Success$i <br>";
}
for ($i = 0; $i < 80; $i++) {
	$r = rand(842, 844);
	$u = $args[$i][0];
	$d = date("Y-m-d H:i:s");
	mysqli_query($con, "insert into voto (cd_usuario, cd_enquete, cd_pergunta, cd_resposta, dt_voto) values ($u, 255, 196, $r, '$d')");
	sleep(1); 
	echo "Success$i <br>";
}*/
$a = array('a', 'b', NULL, 'd');
echo sizeof($a).' '.count($a);
//include 'head.php';
?>
<span class="glyphicon glyphicon-ok" style="color:#00CC00"></span><span class="glyphicon glyphicon-remove"style="color:#CC0000"></span>