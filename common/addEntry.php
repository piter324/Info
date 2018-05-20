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

	$query="INSERT INTO entries (uid,content,link,author,scope,added_date,added_time) VALUES ('".$uid."','".$_POST['tekst']."','".$_POST['link']."','".$_SESSION['userid']."','".$_POST['scope']."','".date('Y-m-d')."','".date('H:i:s')."')";
	mysqli_query($con,$query);
	echo "done";
?>