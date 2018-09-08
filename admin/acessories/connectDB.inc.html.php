<?php
	try
	{
		$pdo = new PDO('mysql:host=localhost;dbname=id5949169_coder','id5949169_admin',$password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$pdo->exec("SET NAMES 'utf8';");
	}
	catch(PDOException $e)
	{
		$error = "Failed to connect to the database: ".$e->getMessage();
		include $_SERVER['DOCUMENT_ROOT'].'/error.html.php';
		exit();
	}