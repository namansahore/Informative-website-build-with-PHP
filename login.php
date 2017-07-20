<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
	if (logged_in()) {
		redirect_to("staff.php");
	}
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
			$query="SELECT id, username FROM users WHERE ";
			$query.="password='{$hashed_password}' AND username='{$username}' LIMIT 1";
			$result_set = mysqli_query($connection,$query);
			confirm_query($result_set);
			if (mysqli_num_rows($result_set)==1) {
				//USER FOUND	
				$found_user = mysqli_fetch_array($result_set);
				$_SESSION['user_id'] = $found_user['id'];	
				$_SESSION['username'] = $found_user['username'];		
				redirect_to("staff.php");

			}else{
				$message = "Username/password combination incorrect.<br />
					Please make sure your caps lock key is off and try again.";
			}
		}else{
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
	}else{
		if (isset($_GET['logout']) && $_GET['logout']) {
			$message = "You have been logged out successfully.";
		}
		$username = "";
		$password = "";
	}
?>
<?php include("includes/header.php"); ?>
<table id="structure">
	<tr>
		<td id="navigation">
			<a href="index.php">Return to public site</a><br/>
			<br />
		</td>
		<td id="page">
			<h2>Staff Login</h2>
			<?php if(!empty($message)) echo "<p class=\"message\">".$message."</p>"; ?>
			<?php if(!empty($errors)) display_errors($errors); ?>
			<form action="login.php" method="post">
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
					<td colspan="2"><input type="submit" name="submit" value="Login" /></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
<?php include("includes/footer.php"); ?>