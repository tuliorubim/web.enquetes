<!-- RODAPE COM OS LINKS -->
<div class="bkg-footer">
<div class="container">
	<div class="row">
    <div class="col-md-4">
    <ul class="menu-footer">
        <li><a href="index.php">Home</a></li>
		<li><a href="premium.php">Servi&ccedil;os e pre&ccedil;os</a></li>
		<li><a href="divulgar.php">Consiga bastantes votos</a></li>
        <li><a href="no_seu_site.php">No seu site</a></li>
        <li><a href="resultados.php">Resultados enviesados</a></li>
		<li><a href="politica_de_privacidade.php">Pol&iacute;tica de privacidade</a></li>
    </ul>
    </div>
    <div class="col-md-4">
    <ul class="menu-footer">
		<li><a href="why_create_poll.php">Por que criar enquetes?</a></li>
        <li><a <?php
			if ($we->idu === 0)
				echo "href='' data-toggle='modal' data-target='.bs-example-modal-sm'";
			else echo "href='criar_enquete.php'";
			?>>Criar enquete</a></li>
        <li><a href="termos_uso.php">Termos de Uso</a></li>
        <li><a href="minhas_enquetes.php">Minhas enquetes</a></li>
        <li><a href="excluir_dados.php">Excluir meus dados deste site</a></li>
        <li><a href="contato.php">Fale Conosco</a></li>
    </ul>
    </div>
    <div class="col-md-4">
    <a href="https://www.facebook.com/WebEnquetesEPesquisas/"><img src="img/facebook.png" class="face" alt="Nossa pagina no facebook"></a>
    </div>
    </div>
</div>
</div>
<?php
mysqli_close($we->con);
?>
