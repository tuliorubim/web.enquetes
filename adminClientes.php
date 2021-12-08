<?php
include "bd.php";
$cds = $_POST['service'];
$idc = $_POST['idCliente'];
$email = $_POST['email'];
if ($cds == 1) $period = (int) $_POST['period'];
$service = new Service($idc);
$service->con = $we->con;
if ($we->idu == 55291 && !empty($idc)) {
	if ($cds == 1) {
		$service->acquire_premium_service ($period, $email);
		echo $service->status;
	} else {
		$service->cancel_premium_service();
		echo $service->status;
	}
} else echo "Nenhum cliente foi especificado ou voc&ecirc; n&atilde;o tem autoriza&ccedil;&atilde;o para esta opera&ccedil;&atilde;o."
?>