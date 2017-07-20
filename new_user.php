<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	include_once("includes/form_functions.php");

	if(isset($_POST['submit'])){
		$errors=array();

		$required_fields=array('username','password');
		$errors=array_merge($errors,check_required_fields($required_fields));

		$fields_with_lengths = array('username' => 30,'password' => 30);
		$errors=array_merge($errors,check_max_field_lengths($fields_with_lengths));

		$username=trim(mysqli_prep($_POST['username']));
		$password=$_POST['password'];
		$hashed_password=md5(sha1($password));

		if (empty($errors)) {
			$query="SELECT * FROM users WHERE username='{$username}' ";
			$result = mysqli_query($connection,$query);
			if ($result) {
				$message = "This user already exists."."<br />";
			}else{
				$query = "INSERT INTO users (username, password) VALUES ('{$username}','{$hashed_password}')";
				$result = mysqli_query($connection,$query);
				if ($result) {
					$message = "The user was successfully created.";
				}else{
					$message = "The user could not be created."."<br />";
					$message .= mysqli_error($connection); 
				}
			}
		}else{
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
	}else{
		$username = "";
		$password = "";
	}
?>
<?php include("includes/header.php"); ?>
<table id="structure">
	<tr>
		<td id="navigation">
			<a href="staff.php">Return to Menu</a><br/>
			<br />
		</td>
		<td id="page">
			<h2>Create New User</h2>
			<?php if(!empty($message)) echo "<p class=\"message\">".$message."</p>"; ?>
			<?php if(!empty($errors)) display_errors($errors); ?>
			<form action="new_user.php" method="post">
			<table>
				<tr>
					<td>Username:</td>
					<td><input type="text" name="username" maxlength="30" value="" /></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><input type="password" name="password" maxlength="30" value="" /></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="submit" value="Create user" /></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
<?php include("includes/footer.php"); ?>