<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	$errors=array();
	//Form Validation
	$required_fields=array('menu_name','position','visible');
	foreach ($required_fields as $fieldname) {
		if(is_numeric($_POST[$fieldname])){
			// This is for visible b/c it will equal to zero sometime
		}
		elseif (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])){
			$errors[]=$fieldname;
		}
	}

	$fields_with_length=array('menu_name'=>30);
	foreach ($fields_with_length as $length => $maxlength) {
		if(strlen(trim(mysqli_prep($_POST[$fieldname]))) > $maxlength){
			$errors[]=$fieldname;
		}
	}

	if (!empty($errors)) {
		redirect_to("new_subject.php");
	}
?>
<?php 
	$menu_name = mysqli_prep($_POST['menu_name']);
	$position = mysqli_prep($_POST['position']);
	$visible = mysqli_prep($_POST['visible']);
?>
<?php
	$query = "INSERT INTO subjects(menu_name,position,visible) VALUES ('{$menu_name}',{$position},{$visible})";
	if(mysqli_query($connection,$query)) {
		//Success!
		redirect_to("content.php");
	}else{
		//Display error message
		echo "<p>Subject creation failed.</p>";
		echo "<p>".mysqli_error()."</p>";
	}
?>

<?php mysqli_close($connection) ?>