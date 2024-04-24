<?php

$usuario = "id21704484_historia";
$senha = "#Ahistoria2023";
$database = "id21704484_livriaria";
$host = "localhost";

$mysqli = new mysqli($host, $usuario, $senha, $database);

if($mysqli -> error){
    die ("Conexão com banco de dados falhou!" . $mysqli-> error);
}

?>
