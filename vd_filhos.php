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

    if(isset($_POST['confirma'])){
    	$n_filhos = $_POST['numeroFilhos'];
    	$validacao = true;

    	$valor = 1000 * $n_filhos;

    	//busca a quantidade de dinheiro e numero de filhos
    	$select = "select dinheiroJogada, filhosJogada from jogada J where idJogada = ".$idjogada."";
		$query_all = mysqli_query($con,$select);
    	$dados_atuais = mysqli_fetch_array($query_all,MYSQLI_ASSOC);

    	$up_jogada = array();

    	if(isset($_POST['automatico'])){
    		$montante = 0;
    		$sucesso = 0;

    		//rodar cada jogador e pagar os filhos
    		$select_outros_gamers = "
			select G.nomeJogador, J.idJogada, J.dinheiroJogada from 
			jogada J left join jogador G on G.idJogador = J.jogador_id
			where J.partida_id = '".$idPartida."' and J.idJogada <> ".$idjogada."
			";
			$query_gamers = mysqli_query($con,$select_outros_gamers);
			while ($gamer = mysqli_fetch_array($query_gamers,MYSQLI_ASSOC)) {

				//verifica se o jogador tem dinheiro
				if($gamer['dinheiroJogada'] > $valor){

					$up_perda = array();
					$up_perda['dinheiroJogada'] = $gamer['dinheiroJogada'] - $valor;
        			$origem = $gamer['idJogada'];
					$destino = $idjogada;
        			$texto = $gamer['nomeJogador']." PAGOU R$ ".dinheiro($valor)." DE PRESENTE PARA ".$nome;
        			$update_perda = "update jogada set ".montaUpdate($up_perda)." where idJogada = ".$gamer['idJogada'];
			    	mysqli_query($con,$update_perda);
			    	historico($con, $idPartida, $origem, $destino, $texto, $valor);
        			$montante += $valor;
        			$sucesso++;

				}else{
        			$origem = $gamer['idJogada'];
					$destino = $idjogada;
        			$texto = "<font color='red'><b>".$gamer['nomeJogador']." NÃO TEVE SALDO PARA DAR PRESENTE PARA ".$nome."</b></font>";
			    	historico($con, $idPartida, $origem, $destino, $texto, NULL);
				}
			}

	    	//busca quanto a pessoa tem
	    	$dinheiro = $dados_atuais['dinheiroJogada'];

			//recebi de todos agr cai na minha conta
			$up_jogada['dinheiroJogada'] = $dinheiro + $montante;
    		$origem = NULL;
    		$destino = $idjogada;
    		$texto = $nome." RECEBEU R$ ".dinheiro($montante)." DE PRESENTE";
	        historico($con, $idPartida, $origem, $destino, $texto, $montante);

    	}

    	//busca o numero de filhos que a pessoa tinha
    	$filhos = $dados_atuais['filhosJogada'];

    	
		$up_jogada['filhosJogada'] = $filhos + $n_filhos;
		$update = "update jogada set ".montaUpdate($up_jogada)." where idJogada = ".$idjogada;
		if(mysqli_query($con,$update)){
			$origem = NULL;
			$destino = $idjogada;
			if($n_filhos == 1) $texto = $nome." TEVE 1 FILHO";
			if($n_filhos == 2) $texto = $nome." TEVE 2 FILHOS";
            historico($con, $idPartida, $origem, $destino, $texto, NULL);
        }
        // die();
	    echo "<script language='javascript'>location.href='vd_jogo.php'</script>";
    }
?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-row-padding w3-white">
		<center>
			<br/>
			<i class='w3-large fa fa-baby'></i> <span class='w3-large'> Ter filhos</span>
		</center>
		<p>
			<?php echo "Jogador: <b>".$jogador."</b>"; ?>
		</p>
	</div>
	<form class="w3-container w3-white w3-center" action="" method="POST">
		<p>
			<div class="w3-row-padding">
				
				<div class="w3-row">
					<label class="w3-text-teal"><b>Selecione o número de filhos que você teve</b></label>
					<br>
					<!-- (O seguro do carro tem um custo de R$ 1.000) -->
					<input class="w3-radio"  type="radio" name="numeroFilhos" value='1' checked >
					<label>1 filho</label>&nbsp;
					<input class="w3-radio"  type="radio" name="numeroFilhos" value='2' >
					<label>2 filho(s)</label>
					<br/>
					<br/>
					<input class="w3-checkbox"  type="checkbox" name="automatico" value='S' checked > Receber presentes automaticamente (R$ 1.000,00 de cada jogador por cada filho)
				</div>
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="Confirmar ação" name="confirma">&nbsp;
					<a href="vd_jogo.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>