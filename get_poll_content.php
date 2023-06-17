<?php
include "ajax_header.php";
$ide = $_POST['ide'];
$poll = file_get_contents("https://www.webenquetes.com.br/enquete.php?ide=$ide");

?>
