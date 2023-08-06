<div class="col-md-4 col-md-offset-1">
    <!-- ESSE BOX E PARA ADICIONAR NOVOS CONTÉUDOS VOCÊ PODE ADICIONAR O QUE QUISER APAGANDO A TAG P E INSERINDO OUTROS CÓDIGOS AS TAGS H2 E BUTTON NÃO DEVEM SER APAGADAS-->
    <div class="form-cadastro2">
    <?php
	include "form_enquete.php";
	?>
    </div> 
    <div class="box1">
    <h2>Destaques do momento</h2>
    <ul class="list-lateral">
	<?php
	$e = $ed->enquetes_destacadas();
	for ($i = 0; $i < 6; $i++)
		echo "<li><a href='enquete.php?ide=".$e[0][$i][0]."'>".$e[0][$i][1]."<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='bottom' title='".$e[1][$i]."'></span></a></li>";
	?>
    </ul>
    </div>
	<div class="box1">
    <h2>Sua enquete em seu site!</h2>
    <p>Agora voc&ecirc; pode criar sua enquete aqui e baixar o c&oacute;digo HTML da mesma para inclu&iacute;-lo no seu site gratuitamente, e os visitantes do seu site a responder&atilde;o.</p>
    <a href="no_seu_site.php" class="btn btn-success btn-lg bt-box">SAIBA MAIS</a>
    </div>
    
    
   
</div>
