<?php include 'core/bootstrap.php'; ?>
<?php
	if(!loggedIn()){
		header("Location: index.php");
		die();
	}
?>
<?php
	$message_id = $_GET['message_id'];
	$user = $_SESSION['username'];
	$message_count = 0;
		
	$data = mysql_query("SELECT * FROM `private_messages` WHERE `to` = '$user'");
	
	while($row = mysql_fetch_array($data)){
		$message_count++;
	}
	
	$data = mysql_query("SELECT * FROM `private_messages` WHERE `from` = '$user'");
	
	while($row = mysql_fetch_array($data)){
		$message_count++;
	}
	
	if($message_count == 0){
		header('Location: index.php');
	}else{
		if(isset($message_id)){
			mysql_query("UPDATE `private_messages` SET `read` = 1 WHERE `message_id` = $message_id");
		}else{
			header('Location: index.php');
		}
		header('Location: messages.php?deleted');
	}
?>