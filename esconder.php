<?php
include "ajax_header.php";
$ide = $_POST['ide'];
$cds = $_POST['cds'];
$field = $_POST['field'];
$hide = ($_POST['hide'] == 'true') ? 1 : 0;
$json = '{ "status" : ';
if ($field != '') {
	mysqli_query($con, "update enquete set $field = $hide where idEnquete = $ide");
	if (!mysqli_error($con)) $json .= ' "'.$field.$hide.'" }';
	else $json .= ' "failure" }';
} else $json .= ' "" }';
echo $json; 
?>
