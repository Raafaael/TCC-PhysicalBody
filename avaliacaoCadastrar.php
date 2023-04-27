<?php
session_start();

//Faz a conexão com o BD.
require 'conexao.php';

//Verifica o acesso.
if($_SESSION['acesso']=="Professor"){

//Dados do formulário
$id = $_POST["id"];
$peso = $_POST["Peso"];
$altura = $_POST["Altura"];
$peito = $_POST["Peito"];
$ombros = $_POST["Ombros"];
$abdomen = $_POST["Abdomen"];
$cintura = $_POST["Cintura"];
$biceps_dir = $_POST["Biceps_dir"];
$biceps_esq = $_POST["Biceps_esq"];
$coxa_dir = $_POST["Coxa_dir"];
$coxa_esq = $_POST["Coxa_esq"];
$panturrilha_dir = $_POST["Panturrilha_dir"];
$panturrilha_esq = $_POST["Panturrilha_esq"];

//Sql que altera um registro da tabela usuários
$sql = "INSERT INTO medida(ID_usuario, Peso, Altura, Peito, Ombros, Abdomen, Cintura, Biceps_direito, Biceps_esquerdo, Coxa_direita, Coxa_esquerda, Panturrilha_direita, Panturrilha_esquerda) VALUES('$id', '$peso', '$altura', '$peito', '$ombros', '$abdomen', '$cintura', '$biceps_dir', '$biceps_esq', '$coxa_dir', '$coxa_esq', '$panturrilha_dir', '$panturrilha_esq')";

//Executa o sql e faz tratamento de erro.
if ($conn->query($sql) === TRUE) {
  echo "Avaliação registrada com sucesso";
} else {
  echo "Erro: " . $conn->error;
}
    header('Location: avaliacoes.php'); //Redireciona para o form	

//Fecha a conexão.
	$conn->close();
	
//Se o usuário não tem acesso.
} else {
    header('Location: index.html'); //Redireciona para o form
    exit; // Interrompe o Script
}

?> 