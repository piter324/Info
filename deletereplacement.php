<?php
	session_start();
	$config_array = parse_ini_file("../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

	$query="DELETE FROM replacements WHERE id='".$_POST[rid]."'";
	mysqli_query($con,$query);
	$queryupdatetime="UPDATE table_updates SET dzien='".date("d")."', miesiac='".date("m")."', rok='".date("Y")."', godzina='".date("H")."', minuta='".date("i")."', sekunda='".date("s")."' WHERE nazwa_tabeli='replacements'";
	mysqli_query($con,$queryupdatetime);
	echo 'done';
	
	//rped:replaced,rping:replacing,sc:scope,lsn:lessonnumber,rm:roomnumber,sbj:subject
?>
