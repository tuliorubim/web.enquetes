<!-- ESCOLHA DE CATEGORIA DA PAGINA HOME -->
<div class="bkg-categoria-home">
<div class="container">
	<div class="row">
    <div class="col-md-12">
    <h1 class="ajuste">S&atilde;o v&aacute;rias categorias para melhor organizar sua pesquisa.</h1>
    </div>
    </div>
    <div class="row">
    <div class="form-group combo">
		<form name="categ" method="post">
        <select class="form-control input-lg" id="categorias" name="categorias2" onchange="window.location.href = 'enquetes.php?idc='+this.options[this.selectedIndex].value;">
			<option>Escolher categoria</option>
		<?php
		$we->listar_categorias();
		?>	
        </select>
		</form>
		<script language="javascript">
			/*$(document).ready(function () {
				$("#categorias").on("change", function () {
					window.location.href = "enquetes.php?idc="+this.options[this.selectedIndex].value;
				});
			});*/
		</script>
    </div>
    </div>
</div>
</div>