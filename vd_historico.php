<?php session_start(); ?><meta charset="UTF-8">
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

	$idjogada = $_SESSION["id_jogada"];
	$idPartida = $_SESSION['idPartida'];

    //Ver o o que o jogador possui
    $select = "
	select dinheiroJogada, estadoPartida from 
	jogada J
		left join partida P on P.idPartida = J.partida_id
	where idJogada = ".$idjogada."
	";
	$query_all = mysqli_query($con,$select);
    $dados = mysqli_fetch_array($query_all,MYSQLI_ASSOC);

    $estadoPartida = $dados['estadoPartida'];

    //busca historico
    $select_historico = "select * from historico H where partida_id = ".$idPartida." order by idHistorico desc limit 6";
	$query_historico = mysqli_query($con,$select_historico);


?>
	<div class="w3-panel w3-blue  w3-center">
		<p>
		<b>Seu saldo</b><br/>
		R$ <?php echo number_format($dados['dinheiroJogada'],2,',','.'); ?>
		</p>
	</div>

	<div class="w3-panel w3-khaki w3-border  " style="font-size: 12px; text-align: left;">
		<?php
			while ($hist = mysqli_fetch_array($query_historico,MYSQLI_ASSOC)) {
				echo $hist['texto']."<br/>";
			}
		?>

	</div>

<?php
	//verifica se encerrou e cenrra
	if($estadoPartida == 2){
		echo "<script language='javascript'>location.href='vd_fim.php'</script>";
	}
?>