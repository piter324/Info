<?php
	session_start();
	$config_array = parse_ini_file("../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");


	$replaced=='';
	$replacing=='';
	$query="SELECT id,username FROM users WHERE username='".$_POST[rped]."' LIMIT 1";
	$result=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($result))
	{
		$replaced=$row[id];
	}
	if($replaced=='')
	{
		$replaced=$_POST[rped];
	}
	
	$query="SELECT id,username FROM users WHERE username='".$_POST[rping]."' LIMIT 1";
	$result=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($result))
	{
		$replacing=$row[id];
		
	}
	if($replacing=='')
	{
		$replacing=$_POST[rping];
	}
	
	$query="INSERT INTO replacements (replaced,replacing,lesson,room,scope,scopetype,subject,date) VALUES ('$replaced','$replacing','$_POST[lsn]','$_POST[rm]','$_POST[sc]','$_POST[sct]','$_POST[sbj]','$_POST[dt]')";
	mysqli_query($con,$query);
	$queryupdatetime="UPDATE table_updates SET dzien='".date("d")."', miesiac='".date("m")."', rok='".date("Y")."', godzina='".date("H")."', minuta='".date("i")."', sekunda='".date("s")."' WHERE nazwa_tabeli='replacements'";
	mysqli_query($con,$queryupdatetime);
	echo 'done';
	
	//rped:replaced,rping:replacing,sc:scope,lsn:lessonnumber,rm:roomnumber,sbj:subject
?>
