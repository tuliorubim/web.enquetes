<?php
include "bd.php";
if ($we->idu == 55291) {
	$rs = mysqli_query($we->con, "show tables from webenque_enquetes");
	while ($row = mysqli_fetch_array($rs)) {
		$pk = $we->GetPrimaryKeys($row[0]);
		$we->select("select count(".$pk[0][0].") from ".$row[0], array('quant'));
		echo "Quantidade de registros na tabela ".$row[0].": $quant <br>";
	}
}
?>