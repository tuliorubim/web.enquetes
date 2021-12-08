<div class="col-md-4 col-md-offset-1">
    <!-- ESSE BOX E PARA ADICIONAR NOVOS CONTÉUDOS VOCÊ PODE ADICIONAR O QUE QUISER APAGANDO A TAG P E INSERINDO OUTROS CÓDIGOS AS TAGS H2 E BUTTON NÃO DEVEM SER APAGADAS-->
    <div class="form-cadastro2">
    <?php
	if ($_POST["button"] != NULL) {
		cria_enquete();
	} 
	include "form_enquete.php";
	?>
    </div> 
    <div class="box1">
    <h2>Sua enquete em seu site!</h2>
    <p>Agora voc&ecirc; pode criar sua enquete aqui e baixar o c&oacute;digo HTML da mesma para inclu&iacute;-lo no seu site gratuitamente, e os visitantes do seu site a responder&atilde;o.</p>
    <button class="btn btn-success btn-lg bt-box">SAIBA MAIS</button>
    </div>
    <div class="box1">
    <h2>Enquetes Recentes</h2>
    <ul class="list-lateral">
	<?php
	$args = select("select idEnquete, enquete from enquete where disponivel = 1 order by idEnquete desc limit 4");
	for ($i = 0; $args[$i][0] !== NULL; $i++) {
     	echo "<li><a href='enquete.php?ide=".$args[$i][0]."'>".$args[$i][1]."</a></li>";
	}
	?>
    </ul>
    </div>
    
    
   
</div>
