<?php
session_start();
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
    <script language="javascript">
	$(function () {
		FB.api({ method: 'pages.isFan', page_id: '{ARCr1H2NGkxUqQR56SWvbsrltoiVFWRHuqh1K-RrBvJbwcTiKLoGhn-i__AbO3iY31wxg96ST72ggwG0}' }, function(response) {
			if (response) {
			  alert("user has liked the page");
			} else {
			  alert("user has not liked the page");
			}
		});
	});
	</script>
	
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