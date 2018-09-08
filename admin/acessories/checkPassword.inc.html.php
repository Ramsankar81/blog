<?php
	if($_POST['password'] == 'Azhar12#7%90$')
	{
		setcookie('db','',time()-3600,'/admin');
		setcookie('db','Azhar12#7%90$',time()+3600,'/admin');
		header('Location:/admin/admin.php');
		exit();
	}