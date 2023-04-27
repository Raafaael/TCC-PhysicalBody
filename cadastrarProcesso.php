<?php
session_start();
//Conectando com o banco de dados
require "conexao.php";

require_once __DIR__. '/lib/vendor/autoload.php';
require_once __DIR__. '/lib/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__. '/lib/vendor/phpmailer/phpmailer/src/SMTP.php';
require_once __DIR__. '/lib/vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ((isset($_POST['email']))&&(!empty($_POST['email']))){


//Pegando os dados inseridos
$termo = 'v1';
$acesso = 'Aluno';
$condicao = 'Inativo';
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$nome = $_POST['nome']; 
$sexo = $_POST['sexo'];
$celular = $_POST['celular'];
$nascimento = $_POST['nascimento'];
$matricula = rand(100000, 999999);
$data_matricula = date('d/m/Y');

//Criptografando 
$text = $_POST['confirma_senha'];
$hash = password_hash($text, PASSWORD_BCRYPT);

//Chave para confirmar email
$chave = password_hash($email . date('Y-m-d H:i:s'),PASSWORD_DEFAULT);
$_SESSION['chave'] = $chave;

//Verifica email duplicado e retorna erro.
$sql = "SELECT Email FROM usuario WHERE Email='$email'";
$sql2 = "SELECT CPF FROM usuario WHERE CPF='$cpf'";

//Gerando os resultados
$result = $conn->query($sql);
$result2 = $conn->query($sql2);

//Linhas 
$row = $result->fetch_assoc();
$row2 = $result2->fetch_assoc();

//Verificando se já existe o Email no BD
if ($result->num_rows > 0) {
  $retorna = ['sit' => true, 'msg' => '<div class="alert alert-danger" role="alert">Email já existe!</div>'];
  $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">Email já existe!</div>';
  header('refresh:0;url=cadastrar.php');
  echo json_encode($retorna);
  exit;  
}

//Verifica se já existe o CPF no BD
if($result2->num_rows >0){
  $retorna = ['sit' => true, 'msg' => '<div class="alert alert-danger" role="alert">CPF já cadastrado!</div>'];
  $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">CPF já cadastrado!</div>';
  header('refresh:0;url=cadastrar.php');
  echo json_encode($retorna);
  exit;  
}

//inserindo os dados
$sql3 = "INSERT INTO usuario(Matricula, Nome, Email, Senha, Condicao, Acesso, CPF, Data_de_nascimento, Telefone, Sexo, Data_matricula, Versao_termo, Chave) VALUES('$matricula', '$nome', '$email','$hash', '$condicao', '$acesso', '$cpf', '$nascimento', '$celular', '$sexo', '$data_matricula', '$termo', '$chave')";

//Instanciando a váriavel do email  
$mail = new PHPMailer(true);     //Instancia do PHPmailer

//Fazendo a ligação do email
try {
    //Configurações do servidor (gmail)
    
    $mail->isSMTP();      
    $mail->SMTPSecure = 'tls';                                  //Enviar usando TLS
    $mail->Host       = 'smtp.gmail.com';                     //Servidor usado
    $mail->SMTPAuth   = true;                                   //Ativando autenticacao SMTP
    $mail->Username   = 'physicalbody00@gmail.com';                     //Usuario SMTP
    $mail->Password   = 'ntmvyeivprkpnyur';                               //Senha SMTP     
    $mail->Port       = 587;        //Porta usada para TLS

    //Aqui ele tira o erro do SSL e da conexão com o Host
    $mail->SMTPOptions = array(
    'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
    )
    );
    
    //quem envia e recebe
    $mail->setFrom('physicalbody00@gmail.com', 'Physical Body');  //Usuario SMTP e Nome aleatório
    $mail->addAddress($email);     //Email do Destinatario
    $mail->isHTML(true);                                  //Habilitando o uso do HTML
    $mail->charset = 'UTF-8';
    $mail->Subject = 'Confirmar Email';    //Titulo
    $mail->Body    = "Olá, seja bem vindo!!<br><br>Clique no link abaixo para confirmar seu e-mail:<br><br>
    <a href='http://localhost/confirma_email.php?chave=$chave' target='_blank'>Clique Aqui!</a>";   //Corpo
    $mail->AltBody = "Olá, seja bem vindo!!\n\nClique no link abaixo para confirmar seu e-mail:\n\n
    <a href='http://localhost/confirma_email.php?chave=$chave' target='_blank'>Clique Aqui!</a>";
  
  //Impedindo que o email seja enviado, se as senhas não forem iguais
 if($conn->query($sql3) === TRUE){
    $mail->send();
    $_SESSION['msg'] = '<script src="scripts/sweetalert2.js"></script>
    <script src="scripts/custom.js"></script>';
    header('refresh:0;url=cadastrar.php');
  }else{
    header('refresh:0;url=cadastrar.php');
  }

} catch (Exception $e) {
    echo "Mensagem não foi enviada. ERRO: {$mail->ErrorInfo}";   //Mensagem de erro, depois envia para o cadastro novamente
    header('refresh:2;url=index.html');
}

//Mandando os dados para o banco 
mysqli_close($conn);
}
?>
