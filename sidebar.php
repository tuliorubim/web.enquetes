<div class="col-md-4 col-md-offset-1">
    <!-- ESSE BOX E PARA ADICIONAR NOVOS CONTÉUDOS VOCÊ PODE ADICIONAR O QUE QUISER APAGANDO A TAG P E INSERINDO OUTROS CÓDIGOS AS TAGS H2 E BUTTON NÃO DEVEM SER APAGADAS-->
    <div class="form-cadastro2">
    <?php
	include "form_enquete.php";
	?>
    </div> 
    <div class="box1">
    <h2>Enquetes para VOC&Ecirc;</h2>
    <ul class="list-lateral">
	<?php
	$e = $ed->enquetes_destacadas();
	for ($i = 0; $i < 6; $i++)
		echo "<li><a href='enquete.php?ide=".$e[0][$i][0]."'>".$e[0][$i][1]."</a><div class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown'><span id='question$i' class='glyphicon glyphicon-question-sign qf'></span></a>".$e[1][$i]."</div></li>";
	?>
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
		t0 = null;
		t1 = null;
		t2 = null;
		t3 = null;
		t4 = null;
		t5 = null;
		fadeOutAndIn('question0');
		setTimeout("fadeOutAndIn('question1')", 250);
		setTimeout("fadeOutAndIn('question2')", 500);
		setTimeout("fadeOutAndIn('question3')", 750);
		setTimeout("fadeOutAndIn('question4')", 1000);
		setTimeout("fadeOutAndIn('question5')", 1250);
	});
	</script>
    </ul>
    </div>
	<div class="box1">
    <h2>Sua enquete em seu site!</h2>
    <p>Aqui voc&ecirc; pode criar sua enquete e baixar o c&oacute;digo HTML da mesma para inclu&iacute;-lo no seu site, e os visitantes do seu site a responder&atilde;o nele.</p>
    <a href="no_seu_site.php" class="btn btn-success btn-lg bt-box">SAIBA MAIS</a>
    </div>
    
    
   
</div>
