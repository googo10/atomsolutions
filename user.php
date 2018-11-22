<?php 
	$page = 'user';
	require_once('header.php'); 
?>
		
		<div class="content">
		
			<?php 
				if(isset($_SESSION['user'])) {
					$user = get_user();
					$uid = $user['uid'];
					if(isset($_GET['a']) && $_GET['a'] == 'edit') {
						?>
							<div class="col-md-6">
								<h3>Edit account</h3>
								
								<?php
								if(isset($_POST['save'])) {
									$error = 0;
									// Check for empty fields
									foreach($_POST as $key => $value) {
										if(empty($_POST[$key])) {
											$error = 1;
											print '<span class="error_message">All Fields are required!</span>';
											break;
										}
									}
									
									// Check for old password
									$old_password = md5($_POST['old_password']);
									$check_for_old_password = "SELECT * FROM `users` WHERE `uid`='$uid' AND `password`='$old_password'";
									$check_for_old_password_result = $mysqli->query($check_for_old_password);
									if($check_for_old_password_result->num_rows == 0) {
										$error = 1;
										print '<span class="error_message">Old password is not valid!</span>';
									}
									
									// Check for password match
									if($_POST['password1'] != $_POST['password2']) {
										$error = 1;
										print '<span class="error_message">Password does not match the confirm password!</span>';
									}
									
									// Password must be more that 8 symbols
									if (strlen($_POST['password1']) < 8) {
										$error = 1;
										print '<span class="error_message">Password too short!</span>';
									}

									// Password must be secure
									if (!preg_match("#[0-9]+#", $_POST['password1']) || !preg_match("#[a-zA-Z]+#", $_POST['password1'])) {
										$error = 1;
										print '<span class="error_message">Password must include at least one number and one letter!</span>';
									}
									
									// Check for errors
									if(!$error) {
										$username = htmlspecialchars(stripslashes($_POST['username']));
										$email = htmlspecialchars(stripslashes($_POST['email']));
										$first_name = htmlspecialchars(stripslashes($_POST['first_name']));
										$last_name = htmlspecialchars(stripslashes($_POST['last_name']));
										$password = md5($_POST['password1']);
										
										$update_user = "UPDATE `users` SET `first_name`='$first_name', `last_name`='$last_name', `password`='$password' WHERE `uid`='$uid'";
										$result = $mysqli->query($update_user);
										
										header('Location: user.php');
										exit();
									}
								}
								?>
								<form method="post" class="register-form" action="">
									<input type="text" name="first_name" value="<?php echo $user['first_name'];?>" placeholder="First Name" />
									<input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" placeholder="Last Name" />
								
									<input type="text" disabled name="username" value="<?php echo $user['username'];?>" placeholder="Username" />
									
									<input type="text" disabled name="email" value="<?php echo $user['email'];?>" placeholder="E-mail" />
									
									<input type="password" name="old_password" placeholder="Old Password" />
									
									<input type="password" name="password1" placeholder="New Password" />
									<input type="password" name="password2" placeholder="Confirm new password" />
									
									<input type="submit" name="save" value="Save" />
								</form>
							</div>
						<?php
					} else {
						$user_books = get_user_books();
			?>
						<div class="row">
							<div class="col-md-12">
								Name: <?php print $user['first_name'] . ' ' . $user['last_name'];?><br />
								Username: <?php print $user['username'];?><br />
								Email: <?php print $user['email'];?><br />
								Book count: <?php print count($user_books);?><br /><br />
								
								[<a href="user.php?a=edit">Edit account</a>] [<a href="books.php?b=mybooks">My books</a>]
							</div>
						</div>
			<?php }} else { ?>
		
			<div class="row">
				<div class="col-md-6">
					<h3>Login</h3>
				
					<?php
					if(isset($_POST['login'])) {
						if(!empty($_POST['password']) && !empty($_POST['username'])) {
							$username = htmlspecialchars(stripslashes($_POST['username']));
							$password = md5($_POST['password']);
							
							$select_user = "SELECT * FROM `users` WHERE `username`='$username' AND `password`='$password' LIMIT 1";
							$result = $mysqli->query($select_user);
							
							if($result->num_rows != 1) {
								print '<span class="error_message">Incorect user or password!</span>';
							} else {
								$_SESSION['user'] = $username;
								header('Location: ' . $_SERVER['PHP_SELF']);
								exit();
							}
						}
					}
					?>
				
					<form method="post" class="login-form" action="">
						<input type="text" name="username" placeholder="Username" />
						
						<input type="password" name="password" placeholder="Password" />
						
						<input type="submit" name="login" value="Login" />
					</form>
					
					<p class="forgot-password">
						<a href="#">Forgot password?</a>
					</p>
				</div>
				
				<div class="col-md-6">
					<h3>Registration</h3>
					
					<?php
					if(isset($_POST['register'])) {
						$error = 0;
						// Check for empty fields
						foreach($_POST as $key => $value) {
							if(empty($_POST[$key])) {
								$error = 1;
								print '<span class="error_message">All Fields are required!</span>';
								break;
							}
						}
						
						// Check for valid email
						if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
							$error = 1;
							print '<span class="error_message">Invalid email format!</span>';
						}
						
						// Check for password match
						if($_POST['password1'] != $_POST['password2']) {
							$error = 1;
							print '<span class="error_message">Password does not match the confirm password!</span>';
						}
						
						// Password must be more that 8 symbols
						if (strlen($_POST['password1']) < 8) {
							$error = 1;
							print '<span class="error_message">Password too short!</span>';
						}

						// Password must be secure
						if (!preg_match("#[0-9]+#", $_POST['password1']) || !preg_match("#[a-zA-Z]+#", $_POST['password1'])) {
							$error = 1;
							print '<span class="error_message">Password must include at least one number and one letter!</span>';
						}
						
						$username = htmlspecialchars(stripslashes($_POST['username']));
						$email = htmlspecialchars(stripslashes($_POST['email']));
							
						// Check for existing user
						$check_for_existing_user = "SELECT * FROM `users` WHERE `username`='$username' OR `email`='$email'";
						$check_for_existing_user_result = $mysqli->query($check_for_existing_user);
						if($check_for_existing_user_result->num_rows != 0) {
							$error = 1;
							print '<span class="error_message">This user already exist!</span>';
						}
						
						// Check for errors
						if(!$error) {
							$first_name = htmlspecialchars(stripslashes($_POST['first_name']));
							$last_name = htmlspecialchars(stripslashes($_POST['last_name']));
							$password = md5($_POST['password1']);
							$created = time();
							
							$insert_user = "INSERT INTO `users` SET `username`='$username', `password`='$password', `email`='$email', `first_name`='$first_name', `last_name`='$last_name', `created`='$created'";
							$result = $mysqli->query($insert_user);
							
							$_SESSION['user'] = $username;
							header('Location: ' . $_SERVER['PHP_SELF']);
							exit();
						}
					}
					?>
					
					<form method="post" class="register-form" action="">
						<input type="text" name="first_name" value="<?php if(!empty($_POST['first_name'])) { echo $_POST['first_name']; } ?>" placeholder="First Name" />
						<input type="text" name="last_name" value="<?php if(!empty($_POST['last_name'])) { echo $_POST['last_name']; } ?>" placeholder="Last Name" />
					
						<input type="text" name="username" value="<?php if(!empty($_POST['username'])) { echo $_POST['username']; } ?>" placeholder="Username" />
						
						<input type="text" name="email" value="<?php if(!empty($_POST['email'])) { echo $_POST['email']; } ?>" placeholder="E-mail" />
						
						<input type="password" name="password1" placeholder="Password" />
						<input type="password" name="password2" placeholder="Confirm password" />
						
						<input type="submit" name="register" value="Register" />
					</form>
				</div>
			</div>
			
			<?php } ?>
			
		</div>
		
<?php require_once('footer.php'); ?>