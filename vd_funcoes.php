<?php


    function montaInsert($matriz){
        //RODAR O ARRAY E MONTAR UMA STRING PARA O INSERT
        $campos = $valores = "";
        foreach ($matriz as $campo => $valor) {
            if($valor != ""){
                $campos .= $campo.", ";
                $valores .= "'".$valor."', ";
            }
        }

        //TIRA A VIRGULA QUE ACIDENTALMENTE APARECE NO FINAL
        $campos = substr($campos, 0, strlen($campos)-2);
        $valores = substr($valores, 0, strlen($valores)-2);

        //MONTA UM INSERT
        return " ($campos) values ($valores) ";
    }


    function montaUpdate($matriz){

        //RODAR O ARRAY E MONTAR UMA STRING PARA O INSERT
       $campos = "";
        //RODAR O ARRAY E MONTAR UMA STRING PARA O UPDATE
        foreach ($matriz as $campo => $valor) {
            //GERA A STRING
            if($valor == ''){
                $campos .= $campo." = NULL, ";
            }else{
                $campos .= $campo." = '".$valor."', ";
            }
        }
       
        //TIRA A VIRGULA QUE ACIDENTALMENTE APARECE NO FINAL
        $campos = substr($campos, 0, strlen($campos)-2);

        //MONTA UM INSERT
        return " $campos ";
    }

    function historico($con, $idPartida, $origem, $destino, $texto, $valor){
        $new_chat = array();
        $new_chat['partida_id'] = $idPartida;
        $new_chat['origem_id'] = $origem;
        $new_chat['destino_id'] = $destino;
        $new_chat['texto'] = addslashes($texto);
        $new_chat['valor'] = $valor;
        $insert = "insert into historico ".montaInsert($new_chat);
        mysqli_query($con,$insert);
    }

    //função que gera codigos randomicos
    function generatorCodigoRand($tamanho = 8, $maiusculas = true, $minuscula = true, $numeros = true, $simbolos = false) {
        $letrasMin = 'abcdefghijklmnopqrstuvwxyz';
        $letrasMai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-';
        $retorno = '';
        $caracteres = '';

        if ($minuscula) {
            $caracteres .= $letrasMin;
        }
        if ($maiusculas) {
            $caracteres .= $letrasMai;
        }
        if ($numeros) {
            $caracteres .= $num;
        }
        if ($simbolos) {
            $caracteres .= $simb;
        }
        $len = strlen($caracteres);
        for ($n = 1; $n <= $tamanho; $n++) {
            $rand = mt_rand(1, $len);
            $retorno .= $caracteres[$rand - 1];
        }
        return $retorno;
    }

    function dinheiro($valor){
        return number_format($valor,2,',','.');
    }

    function limpaNumero($str){
        return preg_replace("/[^0-9]/", "", $str);
    }

?>
