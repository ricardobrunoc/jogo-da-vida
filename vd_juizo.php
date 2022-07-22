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

    $partida_sel = "select * from partida where idPartida = ".$idPartida;
	$query_sel = mysqli_query($con,$partida_sel);
    $dados_sel = mysqli_fetch_array($query_sel,MYSQLI_ASSOC);

    if($dados_sel['estadoPartida'] == 0)
    	echo "<script language='javascript'>location.href='vd_aguarde.php'</script>";

    if($dados_sel['estadoPartida'] == 2)
    	echo "<script language='javascript'>location.href='vd_fim.php'</script>";

    $jogador = $nome." (".$corCarro.")";

    //busca a quantidade de dinheiro e numero de filhos
	$select = "select dinheiroJogada, filhosJogada from jogada J where idJogada = ".$idjogada."";
	$query_all = mysqli_query($con,$select);
	$dados_atuais = mysqli_fetch_array($query_all,MYSQLI_ASSOC);
	$dinheiro = $dados_atuais['dinheiroJogada'];
	$filhos = $dados_atuais['filhosJogada'];



	if($filhos == 0){
		$frase = " como você não possui filhos no momento não recebe mais nada.";
	}else{
		$frase = " com o dinheiro dos seus filhos você irá receber a quantia de <b>R$ ".dinheiro(48000*$filhos)."</b> reais, totalizando <b>R$ ".dinheiro($dinheiro+(48000*$filhos))."</b>";
	}

	if(isset($_POST['confirmar'])){
    	$opcao = $_POST['caminho'];

    	if($opcao == 1){

    		$up_jogada = array();
			$up_jogada['juizo'] = 1;
    		$up_jogada['dinheiroJogada'] = $dinheiro + (48000 * $filhos);
			$update = "update jogada set ".montaUpdate($up_jogada)." where idJogada = ".$idjogada;
			if(mysqli_query($con,$update)){
				$origem = NULL;
				$destino = $idjogada;
    			
    			$texto = $nome." RECEBEU R$ ".dinheiro((48000*$filhos))." DOS FILHOS";
	            historico($con, $idPartida, $origem, $destino, $texto, (48000 * $filhos));

				$texto = $nome." SE TORNOU UM MILIONÁRIO";
	            historico($con, $idPartida, $origem, $destino, $texto, NULL);
	        }

    		echo "<script language='javascript'>location.href='vd_jogo.php'</script>";
    	}else{

    		echo "<script language='javascript'>location.href='vd_magnata.php'</script>";

    	}

    	

  //   	//busca quanto a pessoa tem
		// $select = "select dinheiroJogada from jogada J where idJogada = ".$idjogada."";
		// $query_all = mysqli_query($con,$select);
  //   	$dados_atuais = mysqli_fetch_array($query_all,MYSQLI_ASSOC);
  //   	$dinheiro = $dados_atuais['dinheiroJogada'];

  //   	$valor = $coisas[$coisa]['valor'];
    	
  //   	$up_jogada = array();

  //   	//verifica se o jogador tem saldo pra tal
		// if($dinheiro > $valor){
		// 	$up_jogada['dinheiroJogada'] = $dinheiro - $valor;

		// 	$destino = NULL;
		// 	$origem = $idjogada;

		// 	$texto = $nome." COMPROU ".mb_strtoupper($coisas[$coisa]['desc'])." POR R$ ".dinheiro($valor);

		// }else{
		// 	$validacao = false;
		// 	$msg_error = "Este jogador não tem saldo suficiente para tal";
		// }

		// switch ($coisa) {
		// 	case 'A': $up_jogada['seguroCasa'] = 1; break;
		// 	case 'B': $up_jogada['seguroVida'] = 1; break;
		// 	case 'C': $up_jogada['seguroCarro'] = 1; break;
		// 	case 'D': $up_jogada['acoesJogada'] = 1; break;
		// }

		// if($validacao == true){

	 //        $update = "update jogada set ".montaUpdate($up_jogada)." where idJogada = ".$idjogada;

	 //        if(mysqli_query($con,$update)){

	 //            historico($con, $idPartida, $origem, $destino, $texto, $valor);

	 //            echo "<script language='javascript'>location.href='vd_jogo.php'</script>";
	 //        }else{
	 //            $msg_error = "Erro ao cadastrar";
	 //        }
		// }

    }



?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-row-padding w3-white">
		<center>
			<br/>
			<i class='w3-large fa fa-gavel'></i> <span class='w3-large'> Dia do Juízo</span>
		</center>
		<p>
			<?php echo "Jogador: <b>".$jogador."</b>"; ?><br/>
			Bem vindo ao dia do juízo, segue algumas infomações sobre sua situação financeira.<br/><br/>
			Você possui atualmente <b>R$ <?php echo dinheiro($dinheiro); ?></b>,<?php echo $frase; ?>. Marque o caminho que você irá trilhar agora:
		</p>
	</div>
	<form class="w3-container w3-white w3-center" action="" method="POST">
		<p>
			<div class="w3-row-padding">
				
				<div class="w3-row">
					<label class="w3-text-teal"><b>Qual será o seu caminho?</b></label>
					<br>
					<!-- (O seguro do carro tem um custo de R$ 1.000) -->
					<input class="w3-radio"  type="radio" name="caminho" value='1'  >
					<label>Se tornar um milionário</label>&nbsp;<br/>
					<input class="w3-radio"  type="radio" name="caminho" value='2' >
					<label>Apostar tudo e tentar ser um magnata</label>
					<br/>
					<br/>
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="Confirmar ação" name="confirmar">&nbsp;
					<a href="vd_jogo.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>