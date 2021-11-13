<?php
include "ajax_header.php";
$json = '';
$ide = $_POST["ide"];
if ($_POST["url"] === NULL) {
	select("select url from enquete where idEnquete = $ide", array("url"));
	if ($url == '') $url = "none";
	$json = '{ "url" : "'.$url.'" }';
} else {
	$url = $_POST["url"];
	$sql = "update enquete set url = '$url' where idEnquete = $ide";
	mysqli_query($con, $sql);
	if (!mysqli_error($con))
		$json = '{ "status" : "Sucesso" }';
	else $json = '{ "status" : "'.mysqli_error($con).'" }';	
}
echo $json;
?>