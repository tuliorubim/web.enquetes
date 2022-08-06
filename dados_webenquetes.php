<?php
trait Dados_webenquetes {
	public static $formTabela1;
	public static $formTabela2;
	public static $formTabela3;
	public static $formTabela4;
	public static $formTabela5;
	public static $formTabela6;
	public function setFormTabela1() {
		$variaveis = array("idCategoria", "categoria");
		$labels = array("", "Categoria: ");
		$inputs = array("hidden", "");
		$properties = "class='form-control input-lg'";
		self::$formTabela1 = array($variaveis, NULL, $labels, $inputs, NULL, "categoria", NULL, $properties);
	}
	public function setFormTabela2() {
		$variaveis = array("idEnquete", "cd_categoria", "cd_usuario", "enquete", "introducao", "dt_criacao", "disponivel", "duracao", "code", "url", "esconder", "hide_results", "acima_de_cem", "usar_logo");
		$tipos = array('integer', 'integer', 'integer', 'varchar', 'text', 'datetime', 'boolean', 'integer', 'varchar', "varchar", 'boolean', 'boolean', 'boolean', 'boolean');
		$labels = array("", "", "", "Enquete", 'Introdu&ccedil;&atilde;o ou conte&uacute;do para an&aacute;lise (permite HTML e &eacute; opcional)', "", '', '', '', '', '', '', '', '');
		$inputs = array("hidden", "hidden", "hidden", "text", "textarea", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden");
		$maxlengths = array("", "", "", "64", "65535", '', '', '', '12', '256', '', '', '', '');
		$properties = array();
		$properties[3] = "class='form-control input-lg'";
		$properties[4] = "class='form-control input-lg'";
		$tabela = "enquete";
		self::$formTabela2 = array($variaveis, $tipos, $labels, $inputs, array(), $tabela, $maxlengths, $properties);
	}
	public function setFormTabela3() {
		$variaveis = array("idPergunta", "cd_enquete", "pergunta", "multipla_resposta");
		$tipos = array("integer", "integer", "varchar", "boolean");
		$labels = array("", "", "Pergunta", "Permite escolha de mais de uma op&ccedil;&atilde;o de resposta: ");
		$inputs = array("hidden", "hidden", "text", "checkbox");
		$maxlengths = array("", "", "1024", "");
		$tabela = "pergunta";
		$enderecos = array();
		$properties = array('', '', "class='form-control input-lg'", "onclick = 'this.value = this.checked;'");
		self::$formTabela3 = array($variaveis, $tipos, $labels, $inputs, $enderecos, $tabela, $maxlengths, $properties);
	}
	public function setFormTabela4() {
		$variaveis2 = array("idResposta", "cd_pergunta", "resposta");
		$tipos2 = array("integer", "integer", "varchar");
		$labels2 = array("", "", "Op&ccedil;&atilde;o de resposta");
		$inputs2 = array("hidden", "hidden", "textarea");
		$maxlengths2 = array("", "", "1024");
		$tabela2 = "resposta";
		$enderecos2 = array();
		$properties = array('', '', "class='form-control input-lg'");
		self::$formTabela4 = array($variaveis2, $tipos2, $labels2, $inputs2, $enderecos2, $tabela2, $maxlengths2, $properties, 200);
	}
	public function setFormTabela5($cd_servico) {
		if ($cd_servico > 0) {
			self::$formTabela2[2][13] = "Exibir an&uacute;ncio na enquete?";
			self::$formTabela2[3][13] = "checkbox";
			$variaveis = array("idCliente", "logo", "logoReduzida");
			$tipos = array("integer", "blob", "blob");
			$labels = array("", "An&uacute;ncio em imagem: ", "");
			$inputs = array("hidden", "file", "hidden");
			$maxlengths= array('', '512', '');
			$tabela = "cliente";
			$enderecos = array("logos", "logosReduzidas");
			self::$formTabela5 = array($variaveis, $tipos, $labels, $inputs, $enderecos, $tabela, $maxlengths, array());
		}
	}
	public function setFormTabela6($idu) {
		$variaveis1 = array("idCliente", "nome", "empresa", "site", "logo", "logoReduzida", "data_cadastro", "usuario", "senha", "repsenha");
		$tipos1 = array("integer", "varchar", "varchar", "varchar", "blob", "blob", "datetime", "varchar", "varchar", "varchar");
		$labels1 = array("", "Nome ", "Empresa ", "Seu site ", "An&uacute;ncio em imagem ", '', '', "E-mail $star ", "Senha $star ", "Repetir Senha $star ", true);
		$inputs1 = array("hidden", "text", "text", "text", "file", "hidden", "hidden", "text", "password", "password");
		$enderecos1 = array("logos", "logosReduzidas");
		
		$tabela1 = "cliente";
		$maxlengths1 = array("", "50", "45", "60", '512', '512', '', "256", "12", "12");
		if ($idu !== 0) {
			$variaveis1[8] = NULL;
			$variaveis1[9] = NULL;
			$tipos1[8] = NULL;
			$tipos1[9] = NULL;
			$labels1[8] = NULL;
			$labels1[9] = NULL;
			$inputs1[8] = NULL;
			$inputs1[9] = NULL;
			$maxlengths1[8] = NULL;
			$maxlengths1[9] = NULL;
		}
		$properties1 = "class='form-control input-lg'";
		self::$formTabela6 = array($variaveis1, $tipos1, $labels1, $inputs1, $enderecos1, $tabela1, $maxlengths1, $properties1);
			
	}
}
?>