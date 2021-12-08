<?php
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
	<h1>Assintatura gratuita</h1>
	<?php
	if ($_POST["free_acqr"] === "sim") {
		$data = $service->acquire_premium_service();
		$status = $service->status;
		$we->write_status('status');
	}
	?>
	</div>
    
	<?php include "sidebar.php"; ?>    
    
    </div>
</div>

<?php include "categorias.php"; ?>

<!-- bkg-footer -->
<div class="clearfix">
<?php include "footer.php"; ?>
</div>

</body>
</html>
