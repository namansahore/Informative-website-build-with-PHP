<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	if (intval($_GET['pg'])==0) {
		redirect_to("content.php");
	}

	$id = mysqli_prep($_GET['pg']);
	
	if($page=get_page_by_id($id)){

		$query = "DELETE FROM pages WHERE id={$page[id]} LIMIT 1";
		$result = mysqli_query($connection,$query);
		if (mysqli_affected_rows($connection)==1) {
			redirect_to("edit_subject.php?sub={$page['subject_id']}");
		}else{
			echo "<p>Subject deletion failed.</p>";
			echo "<p>".mysqli_error($connection)."</p>";
			echo "<a href=\"content.php\">Return to main page</a>";
		}

	}else{
		//Subject not found in database
	}
?>
<?php mysqli_close($connection) ?>