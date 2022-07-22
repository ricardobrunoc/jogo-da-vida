<body class="w3-light-grey">
	<?php if(isset($_SESSION['corCarro2'])) $cor = $_SESSION['corCarro2']; else $cor = 'black';  ?>
	<div class="w3-bar w3-top w3-<?php echo $cor; ?> w3-large" style="z-index:4">
  		<a href="index.php"><span class="w3-bar-item w3-center">Jogo da Vida</span></a>
	</div>

