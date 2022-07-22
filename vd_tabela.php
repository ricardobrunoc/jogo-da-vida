<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="library/w3.css">
<link rel="stylesheet" href="library/css.css?family=Raleway">
<link href="fontawesome/css/all.css" rel="stylesheet" />
<script src="library/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="library/jquery-ui.css">
<script src="library/jquery-1.12.4.js"></script>
<script src="library/jquery-ui.js"></script>
<style>
	html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
</style>
<?php
	// echo rand(0,10);
	include "vd_conexao.php";

	$select = "
	select C.w3Cor, C.corCarro, G.nomeJogador, P.estadoPartida from 
	jogada J
		left join partida P on P.idPartida = J.partida_id
		left join jogador G on G.idJogador = J.jogador_id
		left join carro C on C.idCarro = J.carro_id
	where J.partida_id = ".$_GET['partida']."
	";
	$query_all = mysqli_query($con,$select);

	$estadoPartida = 0;


?>
	<table class="w3-table" style='width: 100%' border=1>
		<thead><th colspan=2>Jogadores</th></thead>
		<?php
			while ($dados = mysqli_fetch_array($query_all,MYSQLI_ASSOC)) {
				echo "<tr><td width='20%' class='w3-".$dados['w3Cor']."'>".$dados['corCarro']."</td><td>".$dados['nomeJogador']."</td></tr>";
				$estadoPartida = $dados['estadoPartida'];
			}
		?>
	</table>

<?php
	//verifica se começou e começa
	if($estadoPartida != 0){
		echo "<script language='javascript'>location.href='vd_jogo.php'</script>";
	}
?>