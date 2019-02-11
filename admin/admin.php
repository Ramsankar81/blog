<?php
	if(isset($_COOKIE['db']))
	{
		$password = $_COOKIE['db'];
		include_once $_SERVER['DOCUMENT_ROOT'].'/admin/acessories/connectDB.inc.html.php';
	}
	else
	{
		header('Location:/admin/index.php');
		exit();
	}
	if(isset($_GET['notice']))
	{
		$file = fopen($_SERVER['DOCUMENT_ROOT'].'/acessories/notice.txt','w+') or die('Could not updata notice.txt');
		fwrite($file,$_POST['notice']);
		fclose($file);
		header('Location:/admin/admin.php');
		exit();
	}
	else if(isset($_GET['search']))
	{
		$where = TRUE;
		if(!empty($_POST['name']))
		{
			$name = '%'.$_POST['name'].'%';
			$where = $where." AND name LIKE '$name'";
		}
		if(!empty($_POST['email']))
		{
			$email = '%'.$_POST['email'].'%';
			$where = $where." AND email LIKE '$email'";
		}
		$sql = "SELECT id, name, email FROM users WHERE $where";
		$result=$pdo->query($sql);
		foreach($result as $row)
		{
			$searches[] = array('id'=>$row['id'],'name'=>$row['name'],'email'=>$row['email']);
		}
		include $_SERVER['DOCUMENT_ROOT'].'/admin/acessories/admin.html.php';
		exit();
	}
	else if(isset($_GET['emailUpdate']))
	{
		$email = $_POST['email'];
		$id=$_POST['id'];
		$sql = "UPDATE users SET email = '$email' WHERE id = $id";
		$pdo->exec($sql);
		header('Location:/admin/admin.php');
		exit();
	}
	else if(isset($_GET['question']))
	{
		if(!empty($_POST['question']))
		{
			$file = fopen($_SERVER['DOCUMENT_ROOT'].'/acessories/quest.txt','w+') or die('Could not updata quest.txt');
			fwrite($file,$_POST['question']);
			fclose($file);
		}
		header('Location:/admin/admin.php');
		exit();
	}
	else if(isset($_POST['action']) && $_POST['action'] == 'AddColumn')
	{
		if(!empty($_POST['questName']))
		{
			$column = $_POST['questName'];
			try
			{
				$sql="ALTER TABLE achievement ADD COLUMN $column INT DEFAULT -1;";
				$pdo->exec($sql);
			}
			catch(PDOException $e)
			{
				$error = "Failed to Add Column.";
				include $_SERVER['DOCUMENT_ROOT'].'/error.html.php';
				exit();
			}
			$file = fopen($_SERVER['DOCUMENT_ROOT'].'/acessories/questName.txt','w+') or die('Could not updata questName.txt');
			fwrite($file,$_POST['questName']);
			fclose($file);
		}
		header('Location:/admin/admin.php');
		exit();
	}
	else if(isset($_POST['action']) && $_POST['action'] == 'DeleteColumn')
	{
		if(!empty($_POST['questName']))
		{
			$column = $_POST['questName'];
			try
			{
				$sql="ALTER TABLE achievement DROP COLUMN $column;";
				$pdo->exec($sql);
			}
			catch(PDOException $e)
			{
				$error = "Failed to Delete Column.";
				include $_SERVER['DOCUMENT_ROOT'].'/error.html.php';
				exit();
			}
		}
		header('Location:/admin/admin.php');
		exit();
	}
	else if(isset($_GET['getAnswers']))
	{
		try
		{
			$sql="SELECT filename,users.id,name FROM answers INNER JOIN users ON answers.id = users.id;";
			$result = $pdo->query($sql);
		}
		catch(PDOException $e)
		{
			$error = "Failed to get the list.";
			include $_SERVER['DOCUMENT_ROOT'].'/error.html.php';
			exit();
		}
		foreach($result as $row)
		{
			$answers[] = array('id'=>$row['id'],'name'=>$row['name'],'filename'=>$row['filename']);
		}
		include $_SERVER['DOCUMENT_ROOT'].'/admin/acessories/admin.html.php';
		exit();
	}
	else if(isset($_GET['download']))
	{
		try
		{
			$sql = "SELECT filename, mimetype,filedata FROM answers WHERE id = :id";
			$s=$pdo->prepare($sql);
			$s->bindValue(':id',$_POST['id']);
			$s->execute();
		}
		catch(PDOException $e)
		{
			$error = "Failed to Download.";
			include $_SERVER['DOCUMENT_ROOT'].'/error.html.php';
			exit();
		}
		$file = $s->fetch();
		if(!$file)
		{
			$error = 'File with specified ID not found in the database!';
			include $_SERVER['DOCUMENT_ROOT'].'/error.html.php';
			exit();
		}
		$filename = $file['filename'];
		$mimetype = $file['mimetype'];
		$filedata = $file['filedata'];
		header('Content-length:'.strlen($filedata));
		header("Content-type:$mimetype");
		header("Content-disposition:attachment;filename=$filename");
		echo $filedata;
		exit();
	}
	else if(isset($_GET['deleteAnswers']))
	{
		try
		{
			$sql="DELETE FROM answers;";
			$pdo->exec($sql);
		}
		catch(PDOException $e)
		{
			$error = "Failed to Delete.";
			include $_SERVER['DOCUMENT_ROOT'].'/error.html.php';
			exit();
		}
		header('Location:/admin/admin.php');
		exit();
	}
	else if(isset($_GET['points']))
	{
		$file = fopen($_SERVER['DOCUMENT_ROOT'].'/acessories/questName.txt','r+') or die('Could not updata notice.txt');
		$quest = fgets($file);
		fclose($file);
		$points = $_POST['points'];
		$userid = $_POST['id'];
		$sql = "UPDATE achievement SET $quest = $points WHERE id = $userid";
		$pdo->exec($sql);
		header('Location:/admin/admin.php');
		exit();
	}
	include $_SERVER['DOCUMENT_ROOT'].'/admin/acessories/admin.html.php';
	exit();