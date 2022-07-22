<?php

    // Cria conexão
    $con = mysqli_connect("localhost", "root", "", "vida");
    header('Content-Type: text/html; charset=UTF-8');
    //$con = mysqli_connect("mysql.hostinger.com.br", "u919887589_vida", "@Rick1152", "u919887589_vida");

    mb_internal_encoding("UTF-8");  

    date_default_timezone_set("America/Sao_Paulo");

    mysqli_query($con, "SET NAMES 'utf8'");
    mysqli_query($con, 'SET character_set_connection=utf8');
    mysqli_query($con, 'SET character_set_client=utf8');
    mysqli_query($con, 'SET character_set_results=utf8');

    // if(mysqli_connect_errno())
    // {
    //     echo "Erro ao conectar";
    // }

    error_reporting(E_ALL);
 
/* Habilita a exibição de erros */
//ini_set("display_errors", 1);

?>