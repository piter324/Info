<?php
	session_start();
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

$query="DELETE FROM groups WHERE uid='".$_POST[id]."'";
mysqli_query($con,$query);
$query2="DELETE FROM group_members WHERE groupuid='".$_POST[id]."'";
mysqli_query($con,$query2);
echo "done";

?>
