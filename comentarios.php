<?php
include "ajax_header.php";
$ide = $_POST["ide"];
$comentario = $db->decode($_POST["comment"]);
$idu = $_POST["idu"];
$dt_comentario = date("$dateformat $timeformat");
$res = $db->save('comentario', array('idComentario', 'cd_cliente', 'cd_enquete', 'comentario', 'dt_comentario'), array('integer', 'integer', 'integer', 'varchar', 'datetime'), array(0, $idu, $ide, $comentario, $dt_comentario));
$db->select("select nome, usuario from cliente where idCliente = $idu", array("nome", "usuario"));
if (!empty($nome)) {
	if (strpos($nome, ' ') !== FALSE)
		$r .= substr($nome, 0, strpos($nome, ' '));
	else $r .= $nome;
} elseif (!empty($usuario)) {
	$r .= substr($usuario, 0, strpos($usuario, '@'));
} else $r .= "An&ocirc;nimo";	
$r .= " em ".$db->std_datetime_create($dt_comentario).': ';
$r .= htmlentities($comentario, ENT_QUOTES, 'UTF-8', true)."<br><br>";
$r = str_replace("\n", "<br>", $r);
$json = '{ "comment" : "'.$r.'" }';
echo $json;