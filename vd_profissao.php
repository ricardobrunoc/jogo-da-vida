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

    //Seleciona as profissoes
	$select_profissoes = "select * from profissao order by idProfissao asc";
	$query_profissoes = mysqli_query($con, $select_profissoes);

	if(isset($_POST['definir'])){
		$profissao = $_POST['profissao'];

		//PEGA A PROFISSÃO SELELECIONADA
		$select_prof = "select * from profissao where idProfissao = ".$profissao;
		$query_prof = mysqli_query($con, $select_prof);
		$prof = mysqli_fetch_array($query_prof);
		

        $up_profissao = array();
		$up_profissao['profissao_id'] = $profissao;

        $update = "update jogada set ".montaUpdate($up_profissao)." where idJogada = ".$idjogada;


        if(mysqli_query($con,$update)){

        	$texto = "PROFISSÃO DO(A) ".$nome.": ".$prof['nomeProfissao'];
            historico($con, $idPartida, $idjogada, NULL, $texto, NULL);

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
			<i class='w3-large fa fa-briefcase'></i> <span class='w3-large'> Definir profissão</span>
		</center>
		<p>
			<?php echo "Jogador: <b>".$jogador."</b>"; ?>
		</p>
	</div>
	<form class="w3-container w3-white w3-center" action="" method="POST">
		<p>
			<div class="w3-row-padding">
				
				<div class="w3-row">
					<label class="w3-text-teal"><b>Selecione a profissão</b></label>
					<select class="w3-select" name="profissao">
						<?php
							while ($profissao = mysqli_fetch_array($query_profissoes)) {
								echo "<option class='w3-select' value='".$profissao['idProfissao']."' >".$profissao['nomeProfissao'].": R$ ".dinheiro($profissao['salarioProfissao'])."</option>";
							}
						?>
					</select>
				</div>
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="Definir" name="definir">&nbsp;
					<a href="vd_jogo.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>