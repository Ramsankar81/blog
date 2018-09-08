<?php
	ob_start();
	session_start();
	define('DBHOST','localhost');
	define('DBUSER','user');
	define('DBPASSWORD','password');
	define('DBNAME','blog');
	try{
		$db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$db->exec('SET NAMES "utf8";');
	}
	catch(PDOException $e){
		$error="Unable to connect to the database".$e->getMessage();
		include $_SERVER['DOCUMENT_ROOT']."/error.html.php";
		exit();
	}
	try{
		$sql = 'CREATE TABLE blog_posts(
				postID INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				postTitle VARCHAR(255),
				postDesc TEXT,
				postCont TEXT,
				postDate DATETIME,
				authorid INT(11) NOT NULL DEFAULT 1
			)DEFAULT CHARACTER SET UTF8 ENGINE=InnoDB';
		$db->exec($sql);
	}
	catch(PDOException $e){}
	try{
		$sql = 'CREATE TABLE blog_members(
				memberID INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				username VARCHAR(255) UNIQUE,
				password VARCHAR(255) UNIQUE,
				email VARCHAR(255) UNIQUE
			)DEFAULT CHARACTER SET UTF8 ENGINE=InnoDB';
	$db->exec($sql);
	}catch(PDOException $e){}
	try{
		$sql = 'INSERT INTO blog_members SET
			username = "admin",
			password = "$2y$10$7Tld34zK/qzleZ9mDhoa.OVfZIxKasM1hs6ByoH5fyuZydnE4THey",
			email = "mymail@xyz.com";';
		$db->exec($sql);
	}catch(PDOException $e){}
	function __autoload($class){
		$class=strtolower($class);
		$classpath = $_SERVER['DOCUMENT_ROOT'].'/classes/class.'.$class.'.php';
		if(file_exists($classpath)){
			require_once $classpath;
		}
	}
	$user = new User($db);