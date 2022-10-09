<?php
require_once "funcoes/funcoesDesign.php";
require_once "dados_webenquetes.php";
class Create_HTML extends DesignFunctions {
	use Dados_webenquetes;
	public $idu;
	private $idEnquete;
	private $idc;
	private $extension;
	public $select;
	
	public function __construct($ide=0, $idc=NULL, $ext='html') {
		$this->idEnquete = $ide;
		$this->idc = $idc;
		$this->extension = $ext;
	}
	public function mudou($is_poll=0) {
		$con = $this->con;
		$ide = $this->idEnquete;
		mysqli_query($con, "update poll_html set mudou = 1 where cd_enquete = $ide and is_poll = $is_poll");
	}
	public function save_html_to_file($html, $is_poll=0) {
		$ide = $this->idEnquete;
		$ext = $this->extension;
		$args = $this->select("select code from enquete where idEnquete = $ide", array(), true);
		$enq = ($is_poll) ? "enquete" : "resultados";
		$pollcode  = $args[0]['code'];
		$this->save_file ($html, "$enq$pollcode.$ext", 'w');
	}
	public function save_html_to_db($html, $is_poll=0) {
		$con = $this->con;
		$ide = $this->idEnquete;
		$rs = mysqli_query($con, "select cd_enquete from poll_html where cd_enquete = $ide and is_poll = $is_poll");
		$html = addslashes($html);
		if ($rs && mysqli_fetch_array($rs)) {
			mysqli_query($con, "update poll_html set html = '$html', mudou = 0 where cd_enquete = $ide and is_poll = $is_poll");
		} else mysqli_query($con, "insert into poll_html (cd_enquete, html, is_poll, mudou) values ($ide, '$html', $is_poll, 0)");
	}
	public function select_html_from_db($is_poll=0) {
		$con = $this->con;
		$ide = $this->idEnquete;
		$rs = mysqli_query($con, "select html, mudou from poll_html where cd_enquete = $ide and is_poll = $is_poll");
		if ($rs && $row = mysqli_fetch_array($rs)) {
			return array ($row['mudou'], $row['html']);
		} else return false;
	}
}
?>