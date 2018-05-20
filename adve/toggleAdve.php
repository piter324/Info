<?php
	session_start();
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");
	
	$toInput='Y';
	if($_POST['adveState']==0)
		$toInput='N';

	$query="UPDATE adve SET isOn='".$toInput."' WHERE uid='".$_POST['adveId']."'";
	mysqli_query($con,$query);