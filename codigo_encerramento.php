<?php
	
	$ranking = array();
	$posicao = 1;
	$ganhador_id_magnata = $ganhador_id_milion = $ganhador_id_fallen = '';

	//Encerramento
	//Verifica se tem magnata
	$select_magnata = "select * from jogada where partida_id = '".$idPartida."' and juizo = 2";
	$query_magnata = mysqli_query($con,$select_magnata);
	if(mysqli_num_rows($query_magnata) > 0){
        //Existe magnata

		$dados_magnata = mysqli_fetch_array($query_magnata,MYSQLI_ASSOC);
		$ranking[$dados_magnata['idJogada']] = $posicao;
		$posicao++;
		$ganhador_id_magnata = $dados_magnata['jogador_id'];

    }else{
    	//verifica se todos passaram pelo dia do juizo
    	$select_juizo = "select * from jogada where partida_id = '".$idPartida."' and juizo = 0";
		$query_juizo = mysqli_query($con,$select_juizo);
		if(mysqli_num_rows($query_juizo) > 0){
        	$validacao = false;
        	$msg_error = "Todos os jogadores precisam passar pelo dia do juízo";
        }
    }


    if($validacao == true){

    	//roda os milionarios
    	$select_milions = "select * from jogada where partida_id = '".$idPartida."' and (juizo <> 2 and juizo <> 3) order by dinheiroJogada desc";
		$query_milions = mysqli_query($con,$select_milions);
		if(mysqli_num_rows($query_milions) > 0){
			while ($milion = mysqli_fetch_array($query_milions,MYSQLI_ASSOC)) {
				$ranking[$milion['idJogada']] = $posicao;
				if($posicao == 1)
					$ganhador_id_milion = $milion['jogador_id'];
				$posicao++;

			}
		}

		//roda os falidos
    	$select_fallen = "select * from jogada where partida_id = '".$idPartida."' and (juizo = 3) order by dinheiroJogada desc";
		$query_fallen = mysqli_query($con,$select_fallen);
		if(mysqli_num_rows($query_fallen) > 0){
			while ($fallen = mysqli_fetch_array($query_fallen,MYSQLI_ASSOC)) {
				$ranking[$fallen['idJogada']] = 9;
				if($posicao == 1)
					$ganhador_id_fallen = $fallen['jogador_id'];
				$posicao++;
			}
		}

		//roda o ranking e salva no bd
		foreach ($ranking as $idJog => $posi) {
			$up_jogada = array();
			$up_jogada['ranking'] = $posi;
			$update = "update jogada set ".montaUpdate($up_jogada)." where idJogada = ".$idJog;
			mysqli_query($con,$update);
		}

		//buscar o id jogador do ganahdor
		if($ganhador_id_fallen != '') $ganhador = $ganhador_id_fallen;
		if($ganhador_id_milion != '') $ganhador = $ganhador_id_milion;
		if($ganhador_id_magnata != '') $ganhador = $ganhador_id_magnata;


		//encerra a partida
		$up_partida = array();
        $up_partida['estadoPartida'] = 2;
        $up_partida['dataHoraFinalPartida'] = date('Y-m-d H:i:s');
        $up_partida['ganhador_id'] = $ganhador;

        $update = "update partida set ".montaUpdate($up_partida)." where idPartida = ".$idPartida;
        if(mysqli_query($con,$update)){
            echo "<script language='javascript'>location.href='vd_fim.php'</script>";
        }else{
            $msg_error = "Erro ao encerrar";
        }

    }

?>