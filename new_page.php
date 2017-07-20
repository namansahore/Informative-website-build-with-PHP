<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	// make sure the subject id sent is an integer
	if (intval($_GET['sub']) == 0) {
		redirect_to('content.php');
	}

	include_once("includes/form_functions.php");

	if (isset($_POST['submit'])) {
		$errors = array();
	
		// perform validations on the form data
		$required_fields = array('menu_name', 'position', 'visible', 'content');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));
		
		$fields_with_lengths = array('menu_name' => 30);
		$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths, $_POST));
		
		// clean up the form data before putting it in the database
		$subject_id = mysqli_prep($_GET['sub']);
		$menu_name = trim(mysqli_prep($_POST['menu_name']));
		$position = mysqli_prep($_POST['position']);
		$visible = mysqli_prep($_POST['visible']);
		$content = mysqli_prep($_POST['content']);
	
		if (empty($errors)) {
			$query = "INSERT INTO pages (
						menu_name, position, visible, content, subject_id
					) VALUES (
						'{$menu_name}', {$position}, {$visible}, '{$content}', {$subject_id}
					)";
			if ($result = mysqli_query($connection,$query)) {
				// as is, $message will still be discarded on the redirect
				$message = "The page was successfully created.";
				// get the last id inserted over the current db connection
				$new_page_id = mysqli_insert_id();
				redirect_to("content.php?page={$new_page_id}");
			} else {
				$message = "The page could not be created.";
				$message .= "<br />" . mysqli_error($connection);
			}
		} else {
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
		// END FORM PROCESSING
	}
?>
<?php find_selected_page(); ?>
<?php include("includes/header.php"); ?>
<table id="structure">
	<tr>
		<td id="navigation">
			<?php echo navigation($sel_subject, $sel_page, $public = false); ?>
			<br />
			<a href="new_subject.php">+ Add a new subject</a>
		</td>
		<td id="page">
			<h2>Adding New Page</h2>
			<?php if (!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";} ?>
			<?php if (!empty($errors)) { display_errors($errors); } ?>
			
			<form action="new_page.php?sub=<?php echo $sel_subject['id']; ?>" method="post">
				<?php $new_page = true; ?>
				<?php include "page_form.php" ?>
				<input type="submit" name="submit" value="Create Page" />
			</form>
			<br />
			<a href="edit_subject.php?sub=<?php echo $sel_subject['id']; ?>">Cancel</a><br />
		</td>
	</tr>
</table>
<?php include("includes/footer.php"); ?>