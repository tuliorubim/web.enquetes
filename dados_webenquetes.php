<?php
trait Dados_webenquetes {
	public static $formTabela1;
	public static $formTabela2;
	public static $formTabela3;
	public static $formTabela4;
	public static $formTabela5;
	public static $formTabela6;
	public static $formTabela7;
	public static $formTabela8;
	public static $formTabela9;
	public static $formTabela10;
	public function setFormTabela1() {
		$variaveis = array("idCategoria", "categoria");
		$labels = array("", "Categoria: ");
		$inputs = array("hidden", "");
		$properties = "class='form-control input-lg'";
		self::$formTabela1 = array($variaveis, NULL, $labels, $inputs, NULL, "categoria", NULL, $properties);
	}
	public function setFormTabela2() {
		$variaveis = array("idEnquete", "cd_categoria", "cd_usuario", "enquete", "introducao", "dt_criacao", "disponivel", "duracao", "code", "url", "esconder", "hide_results", "acima_de_cem", "usar_logo");
		$tipos = array('integer', 'integer', 'integer', 'varchar', 'varchar', 'datetime', 'boolean', 'integer', 'varchar', "varchar", 'boolean', 'boolean', 'boolean', 'boolean');
		$labels = array("", "", "", "Enquete", 'Introdu&ccedil;&atilde;o', "", '', '', '', '', '', '', '', '');
		$inputs = array("hidden", "hidden", "hidden", "text", "textarea", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden", "hidden");
		$maxlengths = array("", "", "", "64", "1024", '', '', '', '12', '256', '', '', '', '');
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
			if (self::$formTabela7) {
				self::$formTabela7[2][6] = "Exibir an&uacute;ncio na enquete?";
				self::$formTabela7[3][6] = "checkbox";
			} elseif (!isset($_POST['idConteudo'])) {
				self::$formTabela2[2][13] = "Exibir an&uacute;ncio na enquete?";
				self::$formTabela2[3][13] = "checkbox";
			}
			if (self::$formTabela7 || !isset($_POST['idConteudo'])) {
				$variaveis = array("idCliente", "logo", "logoReduzida", 'site');
				$tipos = array("integer", "blob", "blob", 'varchar');
				$labels = array("", "An&uacute;ncio em imagem: ", "", "P&aacute;gina: ");
				$inputs = array("hidden", "file", "hidden", 'text');
				$maxlengths= array('', '512', '', '256');
				$tabela = "cliente";
				$enderecos = array("logos", "logosReduzidas");
				self::$formTabela5 = array($variaveis, $tipos, $labels, $inputs, $enderecos, $tabela, $maxlengths, array());
			}
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
	public function setFormTabela7 () {
		$variaveis = array('idConteudo', 'cd_usuario', 'cd_categoria', 'dt_criacao', 'titulo', 'introducao', 'usar_logo'); 
		$tipos = array('integer', 'integer', 'integer', 'datetime', 'varchar', 'varchar', 'boolean');
		$labels = array('', '', '', '', 'T&iacute;tulo', 'Introdu&ccedil;&atilde;o', '');
		$inputs = array('hidden', 'hidden', 'hidden', 'hidden', 'text', 'textarea', 'hidden');
		$maxlengths = array('', '', '', '', '64', '1024', '');
		$properties = array();
		$properties[4] = "class='form-control input-lg'";
		$properties[5] = "class='form-control input-lg'";
		$tabela = 'conteudo';
		self::$formTabela7 = array($variaveis, $tipos, $labels, $inputs, NULL, $tabela, $maxlengths, $properties);
	}
	public function setFormTabela8() {
		$variaveis = array("idCType", "type");
		$labels = array("", "");
		$inputs = array("hidden", "");
		$properties = "class='form-control input-lg'";
		self::$formTabela1 = array($variaveis, NULL, $labels, $inputs, NULL, "content_type", NULL, $properties);
	}
	public function setFormTabela9 () {
		$variaveis = array('idConteudo', 'content_type', 'texto', 'audio');
		$tipos = array('integer', 'boolean', 'varchar', 'varchar');
		$labels = array('', '', 'Texto', 'Arquivo de audio');
		$inputs = array('hidden', 'hidden', 'textarea', 'file');
		$enderecos = array("audios");
		$maxlengths = array('', '', '65535', '256');
		$properties = array();
		$properties[2] = "class='form-control input-lg'";
		$properties[3] = "class='form-control input-lg'";
		$tabela = 'conteudo';
		self::$formTabela9 = array($variaveis, $tipos, $labels, $inputs, $enderecos, $tabela, $maxlengths, $properties);
	}
	public function setFormTabela10 () {
		$variaveis = array('idImagem', 'imagem', 'imagemReduzida', 'cd_usuario');
		$tipos = array('integer', 'blob', 'blob', 'integer');
		$labels = array('', 'Imagem: ', '', '', true);
		$inputs = array('hidden', 'file', 'hidden', 'hidden');
		$enderecos = array('imagens');
		$maxlengths = array('', '256', '256', '');
		$properties = array('', "class='form-control input-lg'", '', '');
		$tabela = "content_image";
		self::$formTabela10 = array($variaveis, $tipos, $labels, $inputs, $enderecos, $tabela, $maxlengths, $properties, 1);
	}
	public function setFormTabela11 () {
		$variaveis = array('cd_conteudo', 'cd_imagem');
		$tipos = array('integer', 'integer');
		$labels = array('', '');
		$inputs = array('hidden', 'hidden');
		$enderecos = array();
		$maxlengths = array();
		$properties = array();
		$tabela = "conteudo_image";
		self::$formTabela10 = array($variaveis, $tipos, $labels, $inputs, $enderecos, $tabela, $maxlengths, $properties);
	}
}
?>