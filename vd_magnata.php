<?php
	include "vd_checa.php";
	include "vd_cabecalho.php";
	include "vd_topo.php";
	include "vd_conexao.php";

	$nome = $_SESSION["nome_vida"];
	$idjogador = $_SESSION["id_vida"];
	$idjogada = $_SESSION["id_jogada"];
	$idPartida = $_SESSION['idPartida'];
    $codigoPartida = $_SESSION['codigoPartida'];
    $corCarro = $_SESSION['corCarro'];
    $corw3 = $_SESSION['corCarro2'];

    $jogador = $nome." (".$corCarro.")";

    $partida_sel = "select * from partida where idPartida = ".$idPartida;
	$query_sel = mysqli_query($con,$partida_sel);
    $dados_sel = mysqli_fetch_array($query_sel,MYSQLI_ASSOC);

    if($dados_sel['estadoPartida'] == 0)
    	echo "<script language='javascript'>location.href='vd_aguarde.php'</script>";

    if($dados_sel['estadoPartida'] == 2)
    	echo "<script language='javascript'>location.href='vd_fim.php'</script>";

	//busca a quantidade de dinheiro e numero de filhos
	$select = "select dinheiroJogada, filhosJogada from jogada J where idJogada = ".$idjogada."";
	$query_all = mysqli_query($con,$select);
	$dados_atuais = mysqli_fetch_array($query_all,MYSQLI_ASSOC);
	$dinheiro = $dados_atuais['dinheiroJogada'];
	$filhos = $dados_atuais['filhosJogada'];


    if(isset($_POST['confirmar'])){
    	$opcao = $_POST['magnata'];

    	if($opcao == 2){
    		//Não consegui

    		$up_jogada = array();
			$up_jogada['juizo'] = 3;
    		$up_jogada['dinheiroJogada'] = '0';
			$update = "update jogada set ".montaUpdate($up_jogada)." where idJogada = ".$idjogada;
			if(mysqli_query($con,$update)){
				$origem = NULL;
				$destino = $idjogada;
    			
    			$texto = $nome." RECEBEU R$ ".dinheiro((48000*$filhos))." DOS FILHOS";
	            historico($con, $idPartida, $origem, $destino, $texto, (48000 * $filhos));

				$texto = $nome." APOSTOU TUDO (R$ ".dinheiro((48000*$filhos)).") E FOI À FALÊNCIA";
	            historico($con, $idPartida, $origem, $destino, $texto, NULL);
	        }

    		echo "<script language='javascript'>location.href='vd_jogo.php'</script>";
    	}else{

    		//Consegui

    		$up_jogada = array();
			$up_jogada['juizo'] = 2;
    		$up_jogada['dinheiroJogada'] = (48000*$filhos);
			$update = "update jogada set ".montaUpdate($up_jogada)." where idJogada = ".$idjogada;
			if(mysqli_query($con,$update)){
				$origem = NULL;
				$destino = $idjogada;
    			
    			$texto = $nome." RECEBEU R$ ".dinheiro((48000*$filhos))." DOS FILHOS";
	            historico($con, $idPartida, $origem, $destino, $texto, (48000 * $filhos));

				$texto = $nome." APOSTOU TUDO E CONSEGIU SE TORNAR UM MAGNATA";
	            historico($con, $idPartida, $origem, $destino, $texto, NULL);

				$texto = $nome." VENCEU O JOGO";
	            historico($con, $idPartida, $origem, $destino, $texto, NULL);
	        }

    		echo "<script language='javascript'>location.href='vd_magnatafim.php'</script>";


    	}

    }

?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-row-padding w3-white">
		<center>
			<br/>
			<i class='w3-large fa fa-gavel'></i> <span class='w3-large'> Dia do Juízo - Magnata</span>
		</center>
		<p>
			<?php echo "Jogador: <b>".$jogador."</b>"; ?><br/>
			Selecione um número na roleta para apostar toda a sua vida, se você der sorte você se torna um Magnata e vence o jogo, se você não der sorte, você vai à falência e o jogo continua.
		</p>
	</div>
	<form class="w3-container w3-white w3-center" action="" method="POST">
		<p>
			<div class="w3-row-padding">
				
				<div class="w3-row">
					<label class="w3-text-teal"><b>Você conseguiu ser um magnata?</b></label>
					<br>
					<!-- (O seguro do carro tem um custo de R$ 1.000) -->
					<input class="w3-radio"  type="radio" name="magnata" value='1'  >
					<label>Sim</label>&nbsp;<br/>
					<input class="w3-radio"  type="radio" name="magnata" value='2' >
					<label>Não</label>
					<br/>
					<br/>
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="Confirmar ação" name="confirmar">&nbsp;
					<a href="vd_juizo.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>