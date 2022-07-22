<?php
	include "vd_checa.php";
	include "vd_cabecalho.php";
	include "vd_topo.php";
	include "vd_conexao.php";

	$nome = $_SESSION["nome_vida"];
	$idjogador = $_SESSION["id_vida"];
	$idjogada = $_SESSION["id_jogada"];

	if($idjogada == ''){
		echo "<script language='javascript'>location.href='vd_index.php'</script>";
	}


	//fazer as buscas necessárias

	//busca all
	$select = "
	select * from 
	jogada J
		left join partida P on P.idPartida = J.partida_id
		left join jogador G on G.idJogador = J.jogador_id
		left join carro C on C.idCarro = J.carro_id
	where idJogada = ".$idjogada."
	";
	$query_all = mysqli_query($con,$select);
    $dados = mysqli_fetch_array($query_all,MYSQLI_ASSOC);

    //salva os dados na sessão
    $_SESSION['codigoPartida'] = $dados['codigoPartida'];
    $_SESSION['idPartida'] = $dados['idPartida'];
    $_SESSION['corCarro'] = $dados['corCarro'];
    $_SESSION['corCarro2'] = $dados['w3Cor'];

    $codigo_partida = $dados['codigoPartida'];
    $corCarro = $dados['corCarro'];

    if(isset($_POST['start'])){

        //conta o numero de jogadores
        $select_total = "select count(*) from jogada where partida_id = ".$dados['idPartida'];
		$query_total = mysqli_query($con,$select_total);
		$dadonum = mysqli_fetch_array($query_total,MYSQLI_NUM);
		$numeroJogadores = $dadonum[0];

        $up_partida = array();
        $up_partida['estadoPartida'] = 1;
        $up_partida['dataHoraInicioPartida'] = date('Y-m-d H:i:s');
        $up_partida['numeroJogadores'] = $numeroJogadores;

        $update = "update partida set ".montaUpdate($up_partida)." where idPartida = ".$dados['idPartida'];
        var_dump($update);
        if(mysqli_query($con,$update)){
            //historico($con, $USUARIO, $NOME." editou os dados da turma <b>".$nome_turma."</b>");
            echo "<script language='javascript'>location.href='vd_jogo.php'</script>";
        }else{
            $msg_error = "Erro ao cadastrar";
        }
    }


?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-row-padding w3-white">
		<p>
			Jogador: <b><?php echo $nome; ?></b><br/>
			Código do Jogo: <b><?php echo $codigo_partida; ?></b><br/>
			Carro: <b><?php echo $corCarro; ?></b>
		</p>
	</div>
	<form class="w3-container w3-white w3-center" action="" method="POST">
		<?php include "mensagem.php"; ?>
	<div class="w3-panel w3-red w3-animate-opacity w3-center">
		<p>
		<b>Sala de Espera</b><br/>
		Após o ingresso de todos os participantes, clique em começar o jogo para começar
		<!-- Aguarde o anfitrião começar o jogo -->
		</p>
	</div>
		<p>
			<div class="w3-row-padding">
				<div class="w3-row">
					<div id="load_tabela" class="w3-panel w3-animate-opacity w3-center">
						<table class="w3-table" style='width: 100%' border=1>
							<thead><th colspan=2>Jogadores</th></thead>
							<tr><td width='20%' class="w3-<?php echo $dados['w3Cor']; ?>"><?php echo $corCarro; ?></td><td width='80%'><?php echo $nome; ?></td></tr>
						</table>
					</div>
				</div>
				
				<br/>
				<?php if($dados['jogador_id'] == $dados['host_id']): ?>
					<div class="w3-row">
						<input type="submit" class="w3-btn w3-green w3-center" value="Começar o Jogo" name="start">&nbsp;
					</div>
				<?php endif; ?>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>

<script type="text/javascript">
	var auto_refresh = setInterval(
	function ()
	{
		$('#load_tabela').load('vd_tabela.php?partida=<?php echo $dados['idPartida']; ?>').fadeIn("slow");
	}, 1000); // refresh every 10000 milliseconds
	</script>