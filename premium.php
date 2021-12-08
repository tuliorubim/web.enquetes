<?php
include "bd.php";
require_once "funcoes/funcoesDesign.php";
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
        
        <!-- INICIO / AQUI É O ESPAÇO AONDE VOCÊ IRA COLOCAR O CONTÉUDO DAS OUTRAS PAGINAS INTERNAS QUE VAI APARECER NA COLUNA ESQUERDA -->
    <h1>Nossos Servi&ccedil;os</h1>
	<?php
	require_once 'create_html.php';
	$design = new Create_HTML();
	$design->our_services();
	?>
    <p><b><font color="red">IMPORTANTE:</font> Informe-nos <a href="https://www.facebook.com/WebEnquetesEPesquisas/">aqui</a> sobre a aquisi&ccedil;&atilde;o da sua assinatura e o seu e-mail de cadastro neste site para que possamos fazer sua assinatura entrar em vigor assim que identificarmos o pagamento.</b>
	</p>
	<script language="javascript">
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();
		//$('[title]').css({'position' : 'relative', 'display': 'inline-block'});   
	});
	</script>
	
    <!-- AQUI É O LIMITE PARA A COLUNA ESQUERDA, O CONTEÚDO DEVE ESTAR O ÍNICIO E AQUI O FIM -->
    </div>
    
	<?php include "sidebar.php"; ?>    
    
    </div>
</div>

<?php include "categorias.php"; ?>

<!-- bkg-footer -->
<div class="clearfix">
<?php include "footer.php"; ?>
</div>

</body>
</html>