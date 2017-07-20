<?php

	function check_required_fields($required_array){
		$field_errors = array();
		foreach ($required_array as $fieldname) {
			if($fieldname=='visible'){
				if (($_POST[$fieldname]!=0)&&($_POST[$fieldname]!=1)) {
					$errors[]=$fieldname;
				}
			}
			elseif (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])){
				$field_errors[]=$fieldname;
			}
		}

		return $field_errors;
	}

	function check_max_field_lengths($field_length_array){
		$field_errors=array();
		foreach($field_length_array as $fieldname => $maxlength ) {
			if (strlen(trim(mysqli_prep($_POST[$fieldname]))) > $maxlength) { $field_errors[] = $fieldname; }
		}

		return $field_errors;
	}

	function display_errors($error_array){
		echo "<p";
			if (!empty($error_array)) {
				echo " class=\"errors\">";
				echo "Please review the following fields:<br />";
				foreach($error_array as $error) {
					echo " - " . $error . "<br />";
				}
			}else{
				echo ">";
			}
			echo "</p>";
	}

?>