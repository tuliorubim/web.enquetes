<?php
include "ajax_header.php";
$idu = $_POST["idu"];
mysqli_query($con, "update cliente set permitir_email = 1 where idCliente = $idu");
if (!mysqli_error($con)) echo '{ "success" : "Grato!" }';
?>
