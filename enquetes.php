<?php
include "bd.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php include "head.php"; ?> 
<body>
<?php include "header.php"; ?> 
<div class="container">
	<div class="row">
    <!-- COLUNA ESQUERA -->
    <div class="col-md-7">
        
        <!-- INICIO / AQUI � O ESPA�O AONDE VOC� IRA COLOCAR O CONT�UDO DAS OUTRAS PAGINAS INTERNAS QUE VAI APARECER NA COLUNA ESQUERDA -->
    <?php
	class Search extends AdminFunctions{
		public $con;
		public function __construct($con) {
			$this->con = $con;
		}
		public function search_polls() {
			echo "<h1>";
			$idc = $_GET['idc'];
			$pc = NULL;
			if (is_string($_GET['procurar'])) {
				$pc = mysqli_real_escape_string($this->con, $_GET['procurar']);
			}
			$modelo = $_GET['m'];
			if ($idc !== NULL) {
				$res = $this->select("select categoria from categoria where idCategoria = $idc");
				$categoria = $res[0][0];
				echo "Enquetes da categoria \"$categoria\"";
			} elseif (is_string($pc)) {
				echo "Resultado da busca.";
			} elseif ($modelo == 'true') {
				echo "Enquetes modelo";
			}
			else echo "Todas as enquetes";
			echo "</h1>";
			$sql = "select idEnquete, enquete, dt_criacao from enquete where disponivel = 1 and esconder = 0";
			if ($idc !== NULL) {
				$sql .= " and cd_categoria = $idc";
			} elseif (is_string($pc)) {
				echo $this->procura ($pc, array('divulgar.php', 'no_seu_site.php'), array('enquete.php?ide=', 'resultados_parciais.php?ide='), array("select e.idEnquete, e.enquete, e.introducao, p.pergunta, r.resposta from enquete e inner join pergunta p on e.idEnquete = p.cd_enquete inner join resposta r on p.idPergunta = r.cd_pergunta where e.idEnquete not in (select idEnquete from enquete where esconder = 1)", "select cd_enquete, comentario from comentario where cd_enquete not in (select idEnquete from enquete where esconder = 1)"));
			} elseif ($modelo == 'true') {
				$sql .= " and (cd_usuario = 1 or cd_usuario = 55436 or cd_usuario = 55291)";
				echo "<p>".htmlentities("Nas enquetes abaixo, procuramos elaborar op��es de respostas que tentam abrangir todo o universo de poss�veis respostas �s suas respectivas perguntas, sabendo que responder a uma enquete � escolher a resposta que mais se aproxima do caso de quem a responde. Estude as enquetes abaixo e observe os v�rios aspectos de uma enquete bem elaborada, se voc� achar que isso � necess�rio para voc� criar sua enquete.", ENT_NOQUOTES, 'ISO-8859-1', true)."</p><br>";
			}
			if (!is_string($pc)) {
				$sql .= " order by idEnquete desc";
				$args = $this->select($sql);
				for ($i = 0; $args[$i][0] !== NULL; $i++) {
					echo "<p><div class='nome_enquete'><a href='enquete.php?ide=".$args[$i][0]."'>".$args[$i][1]."</a></div><div class='nome_enquete2'>Criada em: ".$this->std_date_create($args[$i][2])."</div></p>";
				}
			}
		}
	}
	$search = new Search($we->con);
	$search->search_polls();
	?>
    <!-- AQUI � O LIMITE PARA A COLUNA ESQUERDA, O CONTE�DO DEVE ESTAR O �NICIO E AQUI O FIM -->
    </div>
    
	<?php include "sidebar.php"; ?>    
    
    </div>
	<div class="row">
    <div class="col-md-12">
    <div class="bt-all">
    <a href="enquetes.php" class="btn btn-lg btn-success all-enq-home">TODAS AS ENQUETES</a>
	</div>
    </div>
    </div>
</div>

<?php include "categorias.php"; ?>

<!-- bkg-footer -->
<div class="clearfix">
<?php include "footer.php"; ?>
</div>

</body>
</html>