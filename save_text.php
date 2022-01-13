<?php
include "ajax_header.php";
include "dados_webenquetes.php";
Dados_webenquetes::setFormTabela9();
$ft9 = Dados_webenquetes::$formTabela9;
unset($ft9[0][3]);
unset($ft9[1][3]);
$_POST['texto'] = $_POST['textoContent'];
$res1 = $db->save("conteudo", $ft9[0], $ft9[1], array($_POST["idConteudo"], $_POST["content_type"], $_POST["texto"], ''));
$json = '';
if (is_array($res1)) {
	if (!empty($_FILES)) {
		$imagem = $_FILES['imagem'];
		Dados_webenquetes::setFormTabela10();
		$ft10 = Dados_webenquetes::$formTabela10;
		$POST = $_POST;
		if (!empty($_FILES['imagem']['name'])) {
			$imagemReduzida = $imagem;
			$imagemReduzida['name'] = 'thumb'.$imagemReduzida['name'];
		}
		$res2 = $db->save("content_image", $ft10[0], $ft10[1], array(0, $imagem, $imagemReduzida, $idu), $ft10[4]); 
	} elseif (isset($_POST['idImagem'])) {
		$idImagem = (int) $_POST['idImagem'];
	} else $json = '{ "status" : "Nenhuma imagem foi enviada" }';
	Dados_webenquetes::setFormTabela11();
	$ft11 = Dados_webenquetes::$formTabela11;
	if (is_array($res2)) $idImagem = $res2[1];
	if (isset($idImagem)) {	
		$res3 = $db->save("conteudo_image", $ft11[0], $ft11[1], array($res1[1], $idImagem)); 
	}
}
if ($json === '') {
	if (is_array($res1) && is_array($res3)) {
		if (is_array($res2)) {
			$db->select("select imagem from content_image where idImagem = ".$res2[1], array("imagem"));
			$json .= '{ "status" : "success" , "idImagem" : "'.$res2[1].'", "imagem" : "'.$imagem.'" }';
		} elseif (isset($_POST['idImagem'])) {
			$json = '{ "status" : "success2" }';
		}
	} else $json = '{ "status" : "Algo deu errado." }';
}
echo $json;
?>