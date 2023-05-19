<?php
trait Dados_webenquetes2 {
	public static $formTabela6;
	public static function setFormTabela6($idu) {
		$variaveis1 = array("idCliente", "nome", "empresa", "site", "logo", "logoReduzida", "data_cadastro", "usuario", "senha", "repsenha");
		$tipos1 = array("integer", "varchar", "varchar", "varchar", "blob", "blob", "datetime", "varchar", "varchar", "varchar");
		$labels1 = array("", "Nome ", "Empresa ", "Seu site ", "An&uacute;ncio em imagem ", '', '', "E-mail ", "Senha ", "Repetir Senha ", true);
		$inputs1 = array("hidden", "text", "text", "text", "file", "hidden", "hidden", "text", "password", "password");
		$enderecos1 = array("logos", "logosReduzidas");
		
		$tabela1 = "cliente";
		$maxlengths1 = array("", "50", "45", "60", '512', '512', '', "256", "12", "12");
		if ($idu !== 0) {
			unset($variaveis1[8]);
			unset($variaveis1[9]);
			unset($tipos1[8]);
			unset($tipos1[9]);
			//$labels1[8] = true;
			//unset($labels1[9]);
			unset($inputs1[8]);
			unset($inputs1[9]);
			unset($maxlengths1[8]);
			unset($maxlengths1[9]);
		}
		$properties1 = "class='form-control input-lg'";
		self::$formTabela6 = array($variaveis1, $tipos1, $labels1, $inputs1, $enderecos1, $tabela1, $maxlengths1, $properties1);		
	}	
}
class Data_webenquetes2 {
	use Dados_webenquetes2;
}
?>
