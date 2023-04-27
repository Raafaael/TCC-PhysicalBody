<?php
if($_SESSION['acesso']!="Aluno"){
  header('location:login.php');
exit; // Interrompe o Script
}
?>