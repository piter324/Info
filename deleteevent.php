<?php
	session_start();
	$config_array = parse_ini_file("../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

$query="DELETE FROM events WHERE uid='".$_POST[i]."'";
mysqli_query($con,$query);
$query2="DELETE FROM events_scopes WHERE event_uid='".$_POST[i]."'";
mysqli_query($con,$query2);
echo "done";
?>
