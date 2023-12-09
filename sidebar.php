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
		echo "<li id='question$i'><a href='enquete.php?ide=".$e[0][$i][0]."'>".$e[0][$i][1]."</a><div class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown'><span class='glyphicon glyphicon-question-sign qf'></span></a>".$e[1][$i]."</div></li>";
	?>
	<script>
	function fadeOutAndIn (question) {
		num = question.charAt(8);
		eval("t = t"+num+";");
		if (t) {
			clearTimeout(t);
		}
		$('#'+question).fadeTo(2000, 0.2);
		$('#'+question).fadeTo(2000, 1);
		t = setTimeout("fadeOutAndIn('"+question+"')", 4000);
	}
	$(function () {
		t0 = null;
		t1 = null;
		t2 = null;
		t3 = null;
		t4 = null;
		t5 = null;
		fadeOutAndIn('question0');
		setTimeout("fadeOutAndIn('question1')", 500);
		setTimeout("fadeOutAndIn('question2')", 1000);
		setTimeout("fadeOutAndIn('question3')", 1500);
		setTimeout("fadeOutAndIn('question4')", 2000);
		setTimeout("fadeOutAndIn('question5')", 2500);
	});
	</script>
    </ul>
    </div>
	<div class="box1">
    <h2>Sua enquete em seu site!</h2>
    <p>Agora voc&ecirc; pode criar sua enquete aqui e baixar o c&oacute;digo HTML da mesma para inclu&iacute;-lo no seu site gratuitamente, e os visitantes do seu site a responder&atilde;o.</p>
    <a href="no_seu_site.php" class="btn btn-success btn-lg bt-box">SAIBA MAIS</a>
    </div>
    
    
   
</div>
