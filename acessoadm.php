<?php
//Verifica se o usuário logou.
	if($_SESSION['acesso']!="Admin"){
		    header('location:login.php');
			exit; // Interrompe o Script
	}

?>
