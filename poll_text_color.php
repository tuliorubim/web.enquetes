<?php
include "ajax_header.php";
$pb = $_POST['pb'];
$ide = $_POST['ide'];
$c = "<script language='javascript'>\$(document).ready(function () {\$('*').css('color', '#fff'); \$('#responder').css('color', '#000');});</script>";
$f = $ide.'.txt';
if ($pb == '1') {
	$db->save_file ($c, $f);
} else {
	$content = $db->open_file($f);
	$l = strpos($content, $c);
	if ($l !== FALSE) {
		$content = substr($content, 0, $l);
		$db->save_file($content, $f, 'w');
	}
}
echo '{"status" : "Sucesso"}';
?>
