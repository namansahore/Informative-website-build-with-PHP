<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php 
	if (intval($_GET['sub'])==0) {
		redirect_to("content.php");
	}
	if (isset($_POST['submit'])) {
		$errors=array();
		//Form Validation
		$required_fields = array('menu_name', 'position','visible');
		foreach ($required_fields as $fieldname) {
			if($fieldname=='visible'){
				if (($_POST[$fieldname]!=0)&&($_POST[$fieldname]!=1)) {
					$errors[]=$fieldname;
				}
			}
			elseif (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])){
				$errors[]=$fieldname;
			}
		}
		
		$fields_with_lengths = array('menu_name' => 30);
		foreach($fields_with_lengths as $fieldname => $maxlength ) {
			if (strlen(trim(mysqli_prep($_POST[$fieldname]))) > $maxlength) { $errors[] = $fieldname; }
		}

		if (empty($errors)) {
			$id = mysqli_prep($_GET['sub']);
			$menu_name = mysqli_prep($_POST['menu_name']);
			$position = mysqli_prep($_POST['position']);
			$visible = mysqli_prep($_POST['visible']);

			$query = "UPDATE subjects SET
						menu_name = '{$menu_name}',
						position = {$position},
						visible = {$visible}
						WHERE id ={$id}";
			$result = mysqli_query($connection,$query);
			if (mysqli_affected_rows($connection)==1) {
				$message="The subject was successfully updated.";
			}else{
				$message ="No subject update done.";
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
		<br/>
		<br/>
			&nbsp;&nbsp;<a href="logout.php"><b><center>LOGOUT</center></b></a>
		</td>
		<td id="page">
		<h2>Edit Subject:<?php echo $sel_subject['menu_name'] ?></h2>
		<?php if(!empty($message)) echo "<p class=\"message\">".$message."</p>"; ?>
		<?php 
			echo "<p";
			if (!empty($errors)) {
				echo " class=\"errors\">";
				echo "Please review the following fields:<br />";
				foreach($errors as $error) {
					echo " - " . $error . "<br />";
				}
			}else{
				echo ">";
			}
			echo "</p>";
		?>
			<form action="edit_subject.php?sub=<?php echo urldecode($sel_subject['id']); ?>" method="post">
				<p>Subject Name : <input type="text" name="menu_name" value="<?php echo "{$sel_subject['menu_name']}"; ?>" id="menu_name" /></p>
				<p>Position :
					<select name="position">
						<?php 
							$subject_set=get_all_subjects();
							$subject_count=mysqli_num_rows($subject_set);
							// $subject_count + 1 b/c we are adding a subject
							for ($i=1; $i <= $subject_count+1 ; $i++) { 
								echo "<option value=\"{$i}\"";
								if($sel_subject['position']==$i)
									echo " selected";
								echo ">{$i}</option>";
							}
						?>
					</select></p>
				<p>Visible :
					<input type="radio" name="visible" value="0"<?php if($sel_subject['visible']==0) echo "checked";?> />No
					&nbsp;
					<input type="radio" name="visible" value="1"<?php if($sel_subject['visible']==1) echo "checked";?> />Yes
				</p>
				<input type="submit" name="submit" value="Edit Subject" />
				&nbsp; &nbsp;
				<a href="delete_subject.php?sub=<?php echo urlencode($sel_subject['id']); ?>" onclick="return confirm('Are you sure?');">Delete Subject</a>
			</form>
		<br/>
		<a href="content.php">Cancel</a>
		<div style="margin-top: 2em; border-top: 1px solid #000000;">
				<h3>Pages in this subject:</h3>
				<ul>
				<?php 
					$subject_pages = get_pages_for_subject($sel_subject['id']);
					while($page = mysqli_fetch_array($subject_pages)) {
						echo "<li><a href=\"content.php?pg={$page['id']}\">
						{$page['menu_name']}</a></li>";
					}
				?>
				</ul>
				<br />
				+ <a href="new_page.php?sub=<?php echo $sel_subject['id']; ?>">Add a new page to this subject</a>
			</div>
		</td>
	</tr>
</table>
<?php require("includes/footer.php"); ?>