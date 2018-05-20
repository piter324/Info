<?php
	session_start();
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

	$uid=uniqid();

	$queryValidate="SELECT author FROM entries WHERE uid='".$_POST['uid']."'";
	$resultValidate = mysqli_query($con,$queryValidate);
	$rowValidate=mysqli_fetch_array($resultValidate);
	if($rowValidate['author']==$_SESSION['userid'])
	{
		$query="DELETE FROM entries WHERE uid='".$_POST['uid']."' OR parent_uid='".$_POST['uid']."'";
		mysqli_query($con,$query);
		$query="DELETE FROM marks WHERE entry_uid='".$_POST['uid']."' OR entry_parent_uid='".$_POST['uid']."'";
		mysqli_query($con,$query);
		echo "done";
	}
	else
	{
		echo "Nie masz uprawnień do usuwania wpisu";
	}
	
?>