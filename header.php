<?php
require_once('configuration.php');
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Task from Atom-Solutions" />
	<title>Atom-Solutions Book Task</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<div class="container">
		
		<div class="row header-row">
			<div class="col-md-2 header-logo"></div>
			<div class="col-md-7 navigation">
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="books.php">Book List</a></li>
					<li><a href="add_book.php">Add book</a></li>
				</ul>
			</div>
			<div class="col-md-3 user-welcome">
				<span>
					<?php
						if(isset($_SESSION['user'])) {
							print 'Hello,  <a href="user.php">' . $_SESSION['user'] . '</a> | <a href="actions.php?action=logout">Logout</a>';
						} else {
							print '<a href="user.php">Login / Register</a>';
						}
					?>
				</span>
			</div>
		</div>