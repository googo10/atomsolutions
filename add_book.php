<?php 
	$page = 'books';
	require_once('header.php');
?>
		
		<div class="content">
			<div class="row">
				<div class="col-md-6">
					<h3>Add Book</h3>

					<p>
						<?php
						if(isset($_POST['add_book'])) {
							$error = 0;
							// Check for empty fields
							foreach($_POST as $key => $value) {
								if(empty($_POST[$key])) {
									$error = 1;
									print '<span class="error_message">All Fields are required!</span>';
									break;
								}
							}
							
							// Check for numbers only in year field
							if(!is_numeric($_POST['year'])) {
								$error = 1;
								print '<span class="error_message">Year field must be numeric value!</span>';
							}
							
							$target_dir = "uploads/";
							$target_file = $target_dir . basename($_FILES["cover_image"]["name"]);
							$uploadOk = 1;
							$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
							
							// Check if image file is a actual image or fake image
							$check = getimagesize($_FILES["cover_image"]["tmp_name"]);
							if($check === false) {
								$error = 1;
								print '<span class="error_message">This file is not an image!</span>';
							}
							
							// Check file size
							if ($_FILES["cover_image"]["size"] > 500000) {
								$error = 1;
								print '<span class="error_message">This file is too large!</span>';
							}
							
							// Allow certain file formats
							if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
								$error = 1;
								print '<span class="error_message">Only JPG, JPEG, PNG & GIF files are allowed.</span>';
							}
							
							// Check if there is no error
							if (!$error) {
								if (!move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
									print '<span class="error_message">There was an error uploading your file.</span>';
								}
								
								$user = get_user();
								$uid = $user['uid'];
								
								$name = addslashes(htmlspecialchars(stripslashes($_POST['name'])));
								$isbn = htmlspecialchars(stripslashes($_POST['isbn']));
								$year = htmlspecialchars(stripslashes($_POST['year']));
								$description = addslashes(htmlspecialchars(stripslashes($_POST['description'])));
								$created = time();
								
								$insert_book = "INSERT INTO `books` SET `name`='$name', `isbn`='$isbn', `year`='$year', `description`='$description', `image`='$target_file', `created`='$created', `uid`='$uid'";
								$result = $mysqli->query($insert_book);
								print '<span class="success_message">You have successfully added this book!</span>';
							}
						}
						?>
						
						<form method="post" class="add-book-form" enctype="multipart/form-data">
							<input type="text" name="name" placeholder="Name" />
							
							<input type="text" name="isbn" placeholder="ISBN" />
							
							<input type="text" name="year" placeholder="Year" />
							
							<textarea name="description" placeholder="Description"></textarea>
							
							<input type="file" name="cover_image" id="cover_image" />
							
							<input type="submit" name="add_book" value="Add book" />
						</form>
						
					</p>
				</div>
			</div>
		</div>
		
<?php require_once('footer.php'); ?>