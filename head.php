<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $we->title;?></title>
	<meta name="keywords" content="<?php echo $we->description;?>">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:700|Source+Sans+Pro" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<?php
	if (!isset($_GET['ide'])) {
	?>
		<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7212330127162982"
     crossorigin="anonymous"></script>
	<?php 
	} else {
		$we->select("select c.idCliente from cliente c inner join enquete e on c.idCliente = e.cd_usuario where e.idEnquete = ".$_GET['ide'], array("cdu"));
		$service2 = new Service($cdu);
		$service2->con = $we->con;
		$service_data2 = $service2->get_acquired_service();
		$period = $service_data2[0]['periodo_pagamento'];
		$em_vigor = $service_data2[0]['em_vigor'];
		$gratis = $service_data2[0]['gratis'];
		$num_votos = $service2->get_num_votos();
		$limit1 = $service2::LIM_VOTES_NO_ADS1;
		$limit2 = $service2::LIM_VOTES_NO_ADS2;
		if (!$em_vigor || $gratis || ($period == 1 && $num_votos > $limit1) || ($period == 3 && $num_votos > $limit2)) {
	?>
			<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7212330127162982"
     crossorigin="anonymous"></script>
	<?php }} ?>
	<!-- Global site tag (gtag.js) - Google Ads: 948860159 
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-948860159"></script>
	<script>
	 /* window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('set', 'allow_ad_personalization_signals', false);
	  gtag('js', new Date());
	
	  gtag('config', 'AW-948860159');*/
	</script>-->

</head>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>