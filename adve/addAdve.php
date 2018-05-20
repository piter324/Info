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
	$toInput='Y';
	if($_POST['ison']==0)
		$toInput='N';

	$query="INSERT INTO adve (uid,title,content,author,scopetype,isOn,addedDate,addedHour) VALUES ('".$uid."','".$_POST['title']."','".$_POST['content']."','".$_SESSION['userid']."','".$_POST['scopetype']."','".$toInput."','".date('Y-m-d')."','".date('H:i:s')."')";
	mysqli_query($con,$query);

	$scopesArray=explode(",",$_POST['scopes']);
	foreach($scopesArray as $scope)
	{
		$queryScopes="INSERT INTO adve_scopes (adve_uid,scope) VALUES ('".$uid."','".$scope."')";
		mysqli_query($con,$queryScopes);
	}
	echo "done";
?>