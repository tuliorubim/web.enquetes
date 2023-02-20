<?php
trait Dados_webenquetes {
	public static $formTabela1;
	public static $formTabela2;
	public static $formTabela3;
	public static $formTabela4;
	public static $formTabela5;
	public static $formTabela6;
	public static function setFormTabela1() {
		$variaveis = array("idCategoria", "categoria");
		$labels = array("", "Categoria: ");
		$inputs = array("hidden", "");
		$properties = "class='form-control input-lg'";
		self::$formTabela1 = array($variaveis, NULL, $labels, $inputs, NULL, "categoria", NULL, $properties);
	}
	public static function setFormTabela2() {
		$variaveis = array("idEnquete", "cd_categoria", "cd_usuario", "enquete", "introducao", "dt_criacao", "disponivel", "duracao", "code", "url", "esconder", "hide_results", "acima_de_cem", "usar_logo", "tempo_teste", "enquete_ou_prova");
		$tipos = array('integer', 'integer', 'integer', 'varchar', 'varchar', 'datetime', 'boolean', 'integer', 'varchar', "varchar", 'boolean', 'boolean', 'boolean', 'boolean', 'time', 'tinyint');
		$labels = array("", "", "", "Enquete", 'Introdu&ccedil;&atilde;o ou conte&uacute;do para an&aacute;lise (permite HTML e &eacute; opcional)', "", '', '', '', '', '', '', '', '', 'Tempo de dura&ccedil;&atilde;o', '');
		$inputs = array("hidden", "hidden", "hidden", "text", "textarea", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden");
		$maxlengths = array("", "", "", "64", "65535", '', '', '', '12', '256', '', '', '', '', '', '');
		$properties = array();
		$properties[3] = "class='form-control input-lg'";
		$properties[4] = "class='form-control input-lg'";
		$properties[14] = "class='input-lg'";
		$tabela = "enquete";
		self::$formTabela2 = array($variaveis, $tipos, $labels, $inputs, array(), $tabela, $maxlengths, $properties);
	}
	public static function setFormTabela3() {
		$variaveis = array("idPergunta", "cd_enquete", "pergunta", "multipla_resposta", "valor", "cd_resposta_certa");
		$tipos = array("integer", "integer", "varchar", "boolean", "decimal", "integer");
		$labels = array("", "", "Pergunta", "Permite escolha de mais de uma op&ccedil;&atilde;o de resposta: ", "Valor", "");
		$inputs = array("hidden", "hidden", "text", "checkbox", "text", "hidden");
		$maxlengths = array("", "", "1024", "", "4,2", "");
		$tabela = "pergunta";
		$enderecos = array();
		$properties = array('', '', "class='input-lg'", "onclick = 'this.value = this.checked;'", "class='input-lg' style='margin-top:5px'");
		self::$formTabela3 = array($variaveis, $tipos, $labels, $inputs, $enderecos, $tabela, $maxlengths, $properties);
	}
	public static function setFormTabela4() {
		$variaveis2 = array("idResposta", "cd_pergunta", "resposta", "letra", "cd_resposta");
		$tipos2 = array("integer", "integer", "varchar", "char", "");
		$labels2 = array("", "", "Op&ccedil;&atilde;o de resposta", "", 'Resposta certa');
		$inputs2 = array("hidden", "hidden", "textarea", "hidden", "radio");
		$maxlengths2 = array("", "", "1024", '1', '');
		$tabela2 = "resposta";
		$enderecos2 = array();
		$properties = array('', '', "class='input-lg'", '', '');
		self::$formTabela4 = array($variaveis2, $tipos2, $labels2, $inputs2, $enderecos2, $tabela2, $maxlengths2, $properties, 200);
	}
	public static function setFormTabela5($cd_servico) {
		if ($cd_servico > 0) {
			self::$formTabela2[2][13] = "Exibir an&uacute;ncio na enquete?";
			self::$formTabela2[3][13] = "checkbox";
			$variaveis = array("idCliente", "logo", "logoReduzida");
			$tipos = array("integer", "blob", "blob");
			$labels = array("", "An&uacute;ncio em imagem: ", "");
			$inputs = array("hidden", "file", "hidden");
			$maxlengths= array('', '512', '512');
			$tabela = "cliente";
			$enderecos = array("logos", "logosReduzidas");
			self::$formTabela5 = array($variaveis, $tipos, $labels, $inputs, $enderecos, $tabela, $maxlengths, array());
		}
	}
}
class Data_webenquetes {
	use Dados_webenquetes;
}
?>