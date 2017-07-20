<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php find_selected_page(); ?>
<?php include("includes/header.php"); ?>
<table id="structure">
	<tr>
		<td id="navigation">
			<?php echo public_navigation($sel_subject, $sel_page); ?>

		<br/>
		<br/>
			&nbsp;&nbsp;<a href="login.php"><b>Staff Login</b></a>

		</td>
		<td id="page">
			<?php if ($sel_page) { ?>
				<h2><?php echo htmlentities($sel_page['menu_name']); ?></h2>
				<div class="page-content">
					<?php echo strip_tags(nl2br($sel_page['content']),"<b><br><i><p><a>"); ?>
				</div>
			<?php } else { ?>
				<h2>You have to knock first: </h2>
			<?php } ?>
		</td>
	</tr>
</table>
<?php include("includes/footer.php"); ?>