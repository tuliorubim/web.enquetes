<?php
include "bd.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php include "head.php"; ?> 
<body>
<?php include "header.php"; ?>

<form name="sql" method="post">
<textarea name="sql_code" cols="40" rows="3"></textarea><br />
<input type='submit' name="executar" value="Executar" />
</form>
<?php

if ($we->idu == 55291 && $_POST["executar"] == "Executar") {
	$con = $we->con;
	$q = $_POST["sql_code"];
	$rs = mysqli_query($con, $q);
	if (!mysqli_error($con)) {
		if ($rs && strpos($q, "select") == 0) {
			$header = array();
			$row = mysqli_fetch_array($rs);
			$keys = array_keys($row);
			$c = (!is_array($row)) ? 0 : count($row);
			$args = array();
			$html = "<table><tr>";
			for ($i = 0; $i < $c; $i++) {
				$html .= "<th>".$keys[2*$i+1]."</th>";
			}
			$html .= "</tr>";
			if ($rs && mysqli_num_rows($rs) > 0) mysqli_data_seek($rs, 0);
			$j = 0;
			while ($row = mysqli_fetch_array($rs)) {
				$c = (!is_array($row)) ? 0 : count($row);
				$html .= "<tr>";
				for ($i = 0; $i < $c; $i++) {
					$html .= "<td>".$row[$i]."</td>";
				}
				$html .= "</tr>";
				$j++;
			}
			$html .= "</table>";
			echo $html;
		}
		else echo "Comando executado corretamente. Nenhum resultado retornado.";
	} else echo mysqli_error($con);
}
?>
</body>
</html>
