<?php perch_member_log_out(); ?>
<?php
	unset($_SESSION['basiccredits']);
	unset($_SESSION['standardcredits']);
	unset($_SESSION['longcredits']);	
?>
<?php 
	if($_GET['r']){
		header("location:".$_GET['r']);
	}else{
		header("location:/"); 
	}
?>