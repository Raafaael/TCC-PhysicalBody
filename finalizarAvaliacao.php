<?php
session_start();
//Faz a conexão com o BD.
require 'conexao.php';

$id_agendamento = $_GET['agendamento']; 

$sql = "UPDATE agendamento SET status='Realizado' WHERE ID_agendamento='$id_agendamento'";
$result = $conn->query($sql);

//Executa o sql e faz tratamento de erro.
if ($conn->query($sql) === TRUE){
     header('Location: avaliacoes.php'); //Redireciona para o controle  
  }else{
    echo "Erro: " . $conn->error;
  }
?>