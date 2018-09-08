<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin</title>
		<style>
		div{
			padding:5px;
		}
		</style>
	</head>
	<body>
		<h1>NOTICE UPDATE</h1>
		<fieldset><legend>NOTICE</legend>
		<form method="post" action="?notice" id="notice">
			<div>
				<label for="notice">ENTER NOTICE:</label><br><br>
				<textarea name="notice" rows=2 style="width:90%;background:white;"></textarea>
			</div>
			<div>
				<input type="submit" value="Update Notice">
			</div>
		</form>
		</fieldset>
		<h1>EMAIL UPDATE</h1>
		<fieldset><legend>Email</legend>
		<form method="post" action="?search">
			<div>
				NAME:<input type="text" name="name"><br><br>
				E-MAIL:<input type="text" name="email">
			</div>
			<div>
				<input type="submit" value="Search">
			</div>
		</form>
		<?php if(!empty($searches)):?>
			<table border=1 cellspacing=0 cellpadding=20>
				<tr>
					<th>ID</th><th>NAME</th><th>EMAIL</th>
				</tr>
				<?php foreach($searches as $search):?>
				<tr>
					<td><?php echo $search['id']?></td><td><?php echo $search['name']?></td><td><?php echo $search['email']?></td>
				</tr>
				<?php endforeach;?>
			</table>
		<?php endif;?>
		<br><br>
		<form method="post" action="?emailUpdate">
			<div>
				ID<input type="text" name="id"><br><br>
				NEW E-MAIL:ID<input type="text" name="email">
			</div>
			<div>
				<input type="submit" value="Update">
			</div>
		</form>
		</fieldset>
		<h1>QUEST</h1>
		<fieldset>
			<legend>QUEST</legend>
			ENTER QUEST NAME:
			<form method="post" action="">
				<div>
					<input type="text" name="questName">
				</div>
				<div>
					<input type="submit" name="action" value="AddColumn">
					<input type="submit" name="action" value="DeleteColumn">
				</div>
			</form><br><br>
			<form method="post" action="?question" id="question">
				<div>
					<label for="question">ENTER QUESTION:</label><br><br>
					<textarea name="question" rows=2 style="width:90%;background:white;"></textarea>
				</div>
				<div>
					<input type="submit" value="Update Question">
				</div>
			</form>
			<form method="post" action="?getAnswers">
				<div><input type="submit" value="Get Answers"></div>
			</form>
			<?php if(isset($answers) && count($answers)>0):?>
				<table border=1 cellspacing=0 cellpadding=5>
					<tr>
						<td>ID</td><td>NAME</td><td>FILE</td><td>OPTION</td>
					</tr>
					<?php foreach($answers as $answer):?>
						<tr>
							<td><?php echo $answer['id']?></td>
							<td><?php echo $answer['name']?></td>
							<td><?php echo $answer['filename']?></td>
							<td>
								<form method="post" action="?download">
									<input type="hidden" name="id" value="<?php echo $answer['id']?>">
									<input type="submit" value="Download">
								</form>
							</td>
						</tr>
					<?php endforeach;?>
				</table>
			<?php endif;?>
			<form method="post" action="?deleteAnswers">
				<div><input type="submit" value="Delete All Answers"></div>
			</form>
			<br><br>
			ASSIGN POINTS:
			<form method="post" action="?points">
				<div>
					<input type="text" name="id" placeholder="ID">
					<input type="text" name="points" placeholder="POINTS">
					<input type="submit" value="Submit">
				</div>
			</form>
		</fieldset>
	</body>
</html>