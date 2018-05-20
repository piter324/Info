<?php
	session_start();
	$config_array = parse_ini_file("../../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

	$currDate=date('Y-m-d');
	$currTime=date('H:i:s');
	$query="UPDATE modifications SET date_field='".$currDate."',time_field='".$currTime."' WHERE table_name='".$_POST['tablename']."'";
	mysqli_query($con,$query);
	$_SESSION['lastUpdateDate']=$currDate;
	$_SESSION['lastUpdateTime']=$currTime;