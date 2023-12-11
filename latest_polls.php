<!-- ULTIMAS ENQUETES -->
<?php
$args = $ed->enquetes_destacadas();
?>
<div class="container">
	<div class="row">
    <div class="col-lg-12"><h1 class="titu-enquetes">Enquetes para VOC&Ecirc;</h1>
    </div>
    </div>
    <div class="row">
    
    <!-- PARA REUTILIZAR ESSE BLOCO COPIE AQUI AT? A TAG FIM -->
	<?php 
	for ($i = 0; $i < 3; $i++) {
	?>
	<div class="col-md-4">
	<div class="ult-enquetes">
	<h2><span  id="question<?php echo ($i+6);?>"><?php echo $args[0][$i][1];?></span><div class="dropdown"><a href="#" class='dropdown-toggle' data-toggle='dropdown'><span class='glyphicon glyphicon-question-sign qf'></span></a><?php echo $args[1][$i];?></div></h2>
	<a href="enquete.php?ide=<?php echo $args[0][$i][0];?>" class="btn btn-success btn-lg">ENQUETE COMPLETA</a>
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
	<h2><span  id="question<?php echo ($i+6);?>"><?php echo $args[0][$i][1];?></span><div class="dropdown"><a href="#" class='dropdown-toggle' data-toggle='dropdown'><span class='glyphicon glyphicon-question-sign qf'></span></a><?php echo $args[1][$i];?></div></h2>
	<a href="enquete.php?ide=<?php echo $args[0][$i][0];?>" class="btn btn-success btn-lg">ENQUETE COMPLETA</a>
	</div>
	</div>
    <?php } ?>
	<script>
	function fadeOutAndIn (question) {
		num = question.charAt(8);
		eval("t = t"+num+";");
		if (t) {
			clearTimeout(t);
		}
		$('#'+question).fadeTo(1000, 0.2);
		$('#'+question).fadeTo(1000, 1);
		t = setTimeout("fadeOutAndIn('"+question+"')", 2000);
	}
	$(function () {
		t6 = null;
		t7 = null;
		t8 = null;
		t9 = null;
		t10 = null;
		t11 = null;
		fadeOutAndIn('question6');
		setTimeout("fadeOutAndIn('question7')", 250);
		setTimeout("fadeOutAndIn('question8')", 500);
		setTimeout("fadeOutAndIn('question9')", 750);
		setTimeout("fadeOutAndIn('question10')", 1000);
		setTimeout("fadeOutAndIn('question11')", 1250);
	});
	</script>
    </div>
    <div class="row">
    <div class="col-md-12">
    <div class="bt-all">
    <a href="enquetes.php" class="btn btn-lg btn-success all-enq-home">TODAS AS ENQUETES</a>
	</div>
    </div>
    </div>
</div>
