<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	if (intval($_GET['sub'])==0) {
		redirect_to("content.php");
	}

	$id = mysqli_prep($_GET['sub']);
	
	if($subject=get_subject_by_id($id)){

		$query = "DELETE FROM subjects WHERE id={$id} LIMIT 1";
		$result = mysqli_query($connection,$query);
		if (mysqli_affected_rows($connection)==1) {
			redirect_to("content.php");
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