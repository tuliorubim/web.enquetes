<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<form name='form' method='post' enctype='multipart/form-data'>
<?php
include "bd.php";
$variaveis1 = array("idCategoria", "categoria");
$tipos1 = array("integer", "varchar");
$labels1 = array("", "Categoria: $star");
$inputs1 = array("hidden", "text");
$maxlengths1 = array("", "40");
$tabela1 = "categoria";
$enderecos = array();
$formTabela1 = array($variaveis1, $tipos1, $labels1, $inputs1, $enderecos1, $tabela1, $maxlengths1);

$formTabela2 = array();

$formTabela3 = array();

$quantTabela2 = 0;

$categoria = $_POST["categoria"];
$sql = "select * from categoria where categoria like '%$categoria%'";
$select = array($sql);

$SESSION = $_SESSION;
adminPage ($_POST, $_FILES, $SESSION, $formTabela1, $formTabela2, $formTabela3, $select);

?>
<input type='hidden' name='butPres' value=''/>
<input type='button' name='novo' value='Novo' onclick='Novo()'/>
<input type='button' name='salvar' value='Salvar' onclick='ValidaGravacao()'/><input type='button' name='excluir' value='Excluir' onclick='ValidaExclusao(document.form.idCategoria)'/><input type='button' name='selecionar' value='Selecionar' onclick='Selec(document.form.nome)'/></form></body>
</html>
