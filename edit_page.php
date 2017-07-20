<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php 
	if (intval($_GET['pg'])==0) {
		redirect_to("content.php");
	}

	include_once("includes/form_functions.php");

	if (isset($_POST['submit'])) {
		$errors=array();
		//Form Validation
		$required_fields = array('menu_name', 'position','visible','content');
		$errors=array_merge($errors,check_required_fields($required_fields));
		
		$fields_with_lengths = array('menu_name' => 30);
		$errors=array_merge($errors,check_max_field_lengths($fields_with_lengths));

		if (empty($errors)) {
			$id = mysqli_prep($_GET['pg']);
			$menu_name = mysqli_prep($_POST['menu_name']);
			$position = mysqli_prep($_POST['position']);
			$visible = mysqli_prep($_POST['visible']);
			$content = mysqli_prep($_POST['content']);

			$query = "UPDATE pages SET
						menu_name = '{$menu_name}',
						position = {$position},
						visible = {$visible},
						content = '{$content}'
						WHERE id ={$id}";
			$result = mysqli_query($connection,$query);
			if (mysqli_affected_rows($connection)==1) {
				$message="The subject was successfully updated.";
			}else{
				$message ="Page could not updated.";
				$message.="<br/>".mysqli_error($connection);
			}
		}else{
			if (count($errors)==1) {
				$message="There was one error in the form.";
			}else{
				$message="There were ".count($errors)." errors in the form.";
			}
		}
	}
?>
<?php find_selected_page(); ?>
<?php include("includes/header.php"); ?>
<table id="structure">
	<tr>
		<td id="navigation">
		<?php echo navigation($sel_subject,$sel_page); ?>
		</td>
		<td id="page">
		<h2>Edit Page:<?php echo $sel_page['menu_name'] ?></h2>
		<?php if(!empty($message)) echo "<p class=\"message\">".$message."</p>"; ?>
		<?php 
		 	global $errors; 
			display_errors($errors);
		?>
			<form action="edit_page.php?pg=<?php echo urldecode($sel_page['id']); ?>" method="post">
				<?php include("page_form.php"); ?>
				<input type="submit" name="submit" value="Update page" />
				&nbsp; &nbsp;
				<a href="delete_page.php?pg=<?php echo urlencode($sel_page['id']); ?>" onclick="return confirm('Are you sure?');">Delete Page</a>
			</form>
		<br/>
		<a href="content.php?pg=<?php echo $sel_page['id']; ?>">Cancel</a>
		</td>
	</tr>
</table>
<?php require("includes/footer.php"); ?>