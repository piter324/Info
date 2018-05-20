<?php
	session_start();
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

	$queryValidate="SELECT author FROM adve WHERE uid='".$_POST['id']."'";
	$result=mysqli_query($con,$queryValidate);
	$row=mysqli_fetch_array($result);
	if($row['author']==$_SESSION['userid'])
	{
		$query="DELETE FROM adve WHERE uid='".$_POST['id']."' LIMIT 1";
		mysqli_query($con,$query);
		$query="DELETE FROM adve_scope WHERE adve_uid='".$_POST['id']."'";
		mysqli_query($con,$query);
		echo "done";
	}
	else
	{
		echo "Nie masz uprawnień do usunięcia tego ogłoszenia.";
	}