<?php 

	session_start();

	$logado = isset($_SESSION['email']);
	
	if($this->data['restricted'] == true && !$logado)
	{
		header("Location: /csc309hue/");
		die();
	}
?>