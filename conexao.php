<?php

$usuario = "root";
$senha = "BemVindo!";
$database = "login";
$host = "localhost";

$mysqli = new mysqli($host, $usuario, $senha, $database);

if($mysqli -> error){
    die ("Conexão com banco de dados falhou!" . $mysqli-> error);
}

?>
