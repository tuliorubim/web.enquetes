<?php
include "bd.php";
$args = $we->select("select cd_cliente from comentario where cd_cliente not in (select idCliente from cliente)");
for ($i = 0; $args[$i][0] != NULL; $i++) {
	mysqli_query($we->con, "insert into cliente (idCliente, data_cadastro) values (".$args[$i][0].", '".date("Y-m-d H:i:s")."')");
}
?>
