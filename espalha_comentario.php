<?php
include "bd.php";
if ($_SESSION['user'] == 55291) {
	$args = $we->select("select idEnquete from enquete");
	$date = date("Y-m-d H:i:s");
	for ($i = 0; $args[$i][0] !== NULL; $i++) {
		mysqli_query($we->con, "insert into comentario (cd_cliente, cd_enquete, comentario, dt_comentario) values (55291, ".$args[$i][0].", 'https://www.webenquetes.com.br/enquete.php?ide=85', '$date')");
	}
}
if (!mysqli_error($we->con)) echo 'Sucesso';
?>
