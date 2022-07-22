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

    //Ver o o que o jogador possui
    $select = "
	select * from 
	jogada J
		left join partida P on P.idPartida = J.partida_id
		left join profissao T on T.idProfissao = J.profissao_id
	where idJogada = ".$idjogada."
	";
	$query_all = mysqli_query($con,$select);
    $dados = mysqli_fetch_array($query_all,MYSQLI_ASSOC);

    if(isset($_POST['confirma'])){

    	//busca quanto a pessoa tem
		$select = "select dinheiroJogada from jogada J where idJogada = ".$idjogada."";
		$query_all = mysqli_query($con,$select);
    	$dados_atuais = mysqli_fetch_array($query_all,MYSQLI_ASSOC);
    	$dinheiro = $dados_atuais['dinheiroJogada'];

    	$valor = $dados['salarioProfissao'];


    	$up_jogada = array();
    	$up_jogada['dinheiroJogada'] = $dinheiro + $valor;
		$origem = NULL;
		$destino = $idjogada;
		$texto = $nome." RECEBEU SALÁRIO DE R$ ".dinheiro($valor);

        $update = "update jogada set ".montaUpdate($up_jogada)." where idJogada = ".$idjogada;

        if(mysqli_query($con,$update)){

            historico($con, $idPartida, $origem, $destino, $texto, $valor);

            echo "<script language='javascript'>location.href='vd_jogo.php'</script>";
        }else{
            $msg_error = "Erro ao cadastrar";
        }
    }

?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-row-padding w3-white">
		<center>
			<br/>
			<i class='w3-large fa fa-briefcase'></i> <span class='w3-large'> Receber salário</span>
		</center>
		<p>
			<?php echo "Jogador: <b>".$jogador."</b>"; ?>
		</p>
	</div>
	<form class="w3-container w3-white w3-center" action="" method="POST">
		<p>
			<div class="w3-row-padding">
				
				<div class="w3-row">
					<label class="w3-text-teal"><b>Profissão</b></label>
					<input class="w3-input w3-center" name="nome" type="text" value='<?php echo $dados['nomeProfissao']; ?>' disabled>
					
				</div>
				<br/>
				<div class="w3-row">
					<label class="w3-text-teal"><b>Salário</b></label>
					<input class="w3-input w3-center" name="nome" type="text" value='R$ <?php echo dinheiro($dados['salarioProfissao']); ?>' disabled>
					
				</div>
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="Confirmar" name="confirma">&nbsp;
					<a href="vd_jogo.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>