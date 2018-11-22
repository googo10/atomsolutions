<?php 
	$page = 'books';
	require_once('header.php');
?>
		
		<div class="content">
			<div class="row">
				<div class="col-md-12">
					<h3>Books list <span class="book_filter">[<a href="books.php?b=mybooks">My books</a>] [<a href="books.php">All books</a>]</span></h3>

					<div class="row">
						<?php
							$user = get_user();
							// Get only current user books
							if(isset($_GET['b']) && $_GET['b'] == 'mybooks') {
								
								$books = get_user_books();
								if(!$books) {
									print '<p>You have no books. <a href="books.php">View all</a></p>';
								} else {
									
									foreach($books as $book) {
										
										?>
											<div class="col-md-4">
												<a href="books.php?b=<?php print $book['bid'];?>"><img src="<?php print $book['image'];?>" class="img-thumbnail" /></a>
												<p>
													<b>Title:</b> <a href="books.php?b=<?php print $book['bid'];?>"><?php print $book['name'];?></a><br />
													<b>ISBN:</b> <?php print $book['isbn'];?><br />
													<b>Year:</b> <?php print $book['year'];?><br />
													<b>Added:</b> <?php print date('d-m-Y H:i', $book['created']);?><br />
													<b>Description:</b> <?php print substr($book['description'], 0, 50) . '...';?>
												</p>
											</div>
										<?php
										
									}
									
								}
								
							} elseif(isset($_GET['b']) && $books = get_books($_GET['b'])) {
								if(isset($_GET['o']) && ($_GET['o'] == 'edit' || $_GET['o'] == 'delete')) {
									if($_GET['o'] == 'delete') {
										if(isset($_POST['cancel'])) {
											header('Location: books.php?b=' . $_GET['b']);
											exit();
										}
										
										if(isset($_POST['delete'])) {
											$uid = $user['uid'];
											$bid = $_GET['b'];
											$delete_book = "DELETE FROM `books` WHERE `uid`='$uid' AND `bid`='$bid'";
											$result = $mysqli->query($delete_book);
	
											header('Location: books.php?b=mybooks');
											exit();
										}
										?>
											<div class="col-md-12">
												Are you sure that you want to delete this book?
												<form method="post">
													<input type="submit" name="delete" value="Delete" />
													<input type="submit" name="cancel" value="Cancel" />
												</form>
											</div>
										
										<?php
									} elseif($_GET['o'] == 'edit') {
										$books = get_books($_GET['b']);
										foreach($books as $book) {
											if($book['uid'] == $user['uid']) {
												
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
													
													// Check for numbers only in year field
													if(!is_numeric($_POST['year'])) {
														$error = 1;
														print '<span class="error_message">Year field must be numeric value!</span>';
													}
													
													// Check if there is no error
													if (!$error) {
														$uid = $user['uid'];
														$bid = $book['bid'];
														
														$name = addslashes(htmlspecialchars(stripslashes($_POST['name'])));
														$isbn = htmlspecialchars(stripslashes($_POST['isbn']));
														$year = htmlspecialchars(stripslashes($_POST['year']));
														$description = addslashes(htmlspecialchars(stripslashes($_POST['description'])));
														
														$update_book = "UPDATE `books` SET `name`='$name', `isbn`='$isbn', `year`='$year', `description`='$description' WHERE `uid`='$uid' AND `bid`='$bid'";
														$result = $mysqli->query($update_book);
														header('Location: books.php?b=mybooks');
														exit();
													}
												}
											?>
											<div class="col-md-8">
												<h3>Edit <?php print $book['name'];?></h3>
												<form method="post" class="add-book-form">
													<input type="text" name="name" value="<?php print $book['name'];?>" />
													<input type="text" name="isbn" value="<?php print $book['isbn'];?>" />
													<input type="text" name="year" value="<?php print $book['year'];?>" />
												
													<textarea name="description"><?php print $book['description'];?></textarea>
												
													<input type="submit" name="save" value="Save" />
												</form>
											</div>
											<?php
											}
											else {
												header('Location: books.php?b=mybooks');
												exit();
											}
										}
									}
								} else {
									foreach($books as $book) {
										?>
											<div class="col-md-6">
												<img src="<?php print $book['image'];?>" class="img-thumbnail" />
											</div>
											<div class="col-md-6">
												<p>
													<b>Title:</b> <a href="books.php?b=<?php print $book['bid'];?>"><?php print $book['name'];?></a><br />
													<b>ISBN:</b> <?php print $book['isbn'];?><br />
													<b>Year:</b> <?php print $book['year'];?><br />
													<b>Added:</b> <?php print date('d-m-Y H:i', $book['created']);?><br />
													<b>Description:</b> <?php print $book['description'];?><br />
													
													<?php
													if($user['uid'] == $book['uid']) {
														print '<p>[<a href="books.php?b=' . $book['bid'] . '&o=edit">Edit</a>] [<a href="books.php?b=' . $book['bid'] . '&o=delete">Delete</a>]</p>';
													}
													?>
												</p>
											</div>
										<?php
									}
								}
							} else {
								
								if($books = get_books()) {
									foreach($books as $book) {
										
										?>
											<div class="col-md-4">
												<a href="books.php?b=<?php print $book['bid'];?>"><img src="<?php print $book['image'];?>" class="img-thumbnail img-responsive" /></a>
												<p>
													<b>Title:</b> <a href="books.php?b=<?php print $book['bid'];?>"><?php print $book['name'];?></a><br />
													<b>ISBN:</b> <?php print $book['isbn'];?><br />
													<b>Year:</b> <?php print $book['year'];?><br />
													<b>Added:</b> <?php print date('d-m-Y H:i', $book['created']);?><br />
													<b>Description:</b> <?php print substr($book['description'], 0, 50) . '...';?>
												</p>
											</div>
										<?php
										
									}
								} else {
									print '<p>There is no books yest</p>';
								}
								
							}
						?>
					</div>
				</div>
			</div>
		</div>
		
<?php require_once('footer.php'); ?>