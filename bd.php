<?php
$ips = explode (' ', file_get_contents('ipsss.txt'));
if (in_array($_SERVER['REMOTE_ADDR'], $ips)) exit();
//session_save_path("/tmp");
session_start();
//if ($_SERVER['SERVER_PORT'] == 80)
	//echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=https://www.webenquetes.com.br".$_SERVER['REQUEST_URI']."'>";
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE);
require_once "webenquetes.php";
?>
<script>var novo_usuario = false;</script>
<?php
$we = new WebEnquetes();
$GET = $we->array_keys_assign(array('ide'), $_GET);
$we->Connect_WE();
$we->create_session();
if (strpos($_SERVER['REQUEST_URI'], 'criar_enquete.php') !== FALSE && array_key_exists("button", $_POST) && $_POST["button"] != NULL) {
	$idEnquete = $we->cria_enquete();
} 
$we->new_user();
$page = ($GET['ide'] == NULL) ? 0 :$GET['ide'];
$we->contagem ('cont_geral', true, $we->idu, $page);
$we->set_title_and_keywords();

include "service.php";
$service = new Service($we->idu);
$service->con = $we->con;
$service_data = $service->get_acquired_service();

include "enquetes_destacadas.php";
$ed = new EnquetesDestacadas($we->idu, $we->con);
?>
<script src="jquery-3.5.1.min.js"></script>
<script src="funcoes/JSFunctions.js" type="text/javascript"></script>
<div id="fb-root"></div>
<?php
if (strpos($_SERVER['REQUEST_URI'], 'resultados_parciais.php') === FALSE) {
?>
<!--<script async defer crossorigin="anonymous" src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v5.0"></script>-->
<?php
}
include 'funcoes/init.html';
/*$args = select("SELECT v.cd_enquete, v.cd_pergunta, v.dt_voto, e.esconder FROM enquete e inner join voto v on e.idEnquete = v.cd_enquete order by v.cd_enquete, v.cd_pergunta, v.dt_voto desc");
		$enq = array();
		$dif = array();
		$i = 0;
		$j = 0;
		while ($args[$i][0] !== NULL) {
			$d = 1;
			while ($args[$i+$d][1] === $args[$i][1] && $d < 15) 
				$d++;
			select("select count(dt_voto) from voto where cd_enquete = ".$args[$i][0], array('num_votos'));
			if ($num_votos >= 30 && !$args[$i][3]) {	
				$date1 = strtotime2($args[$i+$d-1][2]);
				$date2 = strtotime2(date("Y-m-d H:i:s"));
				$dif[$j] = ($date2-$date1)/$d;
				$enq[$j] = $args[$i][0];
				$j++;	
			}
			$i++;
			while ($args[$i][0] === $args[$i-1][0])
				$i++;
		}
		$j = 0;
		while ($enq[$j] !== NULL) {
			$d = 1;
			while ($dif[$j+$d] !== NULL) {
				if ($dif[$j] > $dif[$j+$d]) {
					$aux = $dif[$j];
					$dif[$j] = $dif[$j+$d];
					$dif[$j+$d] = $aux;
					$aux = $enq[$j];
					$enq[$j] = $enq[$j+$d];
					$enq[$j+$d] = $aux;
				}
				$d++;
			}
			$j++;
		}
		$enquetes = array();
		for ($j = 0; $enq[$j] !== NULL; $j++) {
			select("select enquete from enquete where idEnquete = ".$enq[$j], array("enquete"));
			$enquetes[$j] = array($enq[$j], $enquete);
		}*/
?>