<!-- ULTIMAS ENQUETES -->
<?php
$args = $we->enquetes_mais_procuradas();
?>
<div class="container">
	<div class="row">
    <div class="col-lg-12"><h1 class="titu-enquetes">Destaques do momento</h1>
    </div>
    </div>
    <div class="row">
    
    <!-- PARA REUTILIZAR ESSE BLOCO COPIE AQUI AT? A TAG FIM -->
	<?php 
	for ($i = 0; $i < 3; $i++) {
	?>
	<div class="col-md-4">
	<div class="ult-enquetes">
	<h2><?php echo $args[$i][1];?></h2>
	<a href="enquete.php?ide=<?php echo $args[$i][0];?>" class="btn btn-success btn-lg">ENQUETE COMPLETA</a>
	</div>
	</div>
	<?php } ?>
    <!-- FIM -->
    
    </div>
    <div class="row">
	<?php 
	for ($i = 3; $i < 6; $i++) {
	?>
	<div class="col-md-4">
	<div class="ult-enquetes">
	<h2><?php echo $args[$i][1];?></h2>
	<a href="enquete.php?ide=<?php echo $args[$i][0];?>" class="btn btn-success btn-lg">ENQUETE COMPLETA</a>
	</div>
	</div>
    <?php } ?>
    </div>
    <div class="row">
    <div class="col-md-12">
    <div class="bt-all">
    <a href="enquetes.php" class="btn btn-lg btn-success all-enq-home">TODAS AS ENQUETES</a>
	</div>
    </div>
    </div>
</div>
