<ul class="nav nav-pills">
	<li role="presentation" class="active"><a href="dados.php">Meus Dados</a></li>
	<li role="presentation"><a href="minhas_enquetes.php">Minhas Enquetes</a></li>
	<li role="presentation"><a href="criar_enquete.php">Criar Enquete</a></li>
	<?php
	if (empty($service_data)) {
	?>
		<li role="presentation"><a href="bonus_mensais.php">Assinar</a></li>
	<?php 
	} else { 
	?>
		<li role="presentation"><a href="meu_plano.php">Meu Plano</a></li>
	<?php } ?>
	<li role="presentation"><a href="index.php?login=off">Sair</a></li>
</ul>