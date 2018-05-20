<?php
	session_start();
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");



$members=explode(",",$_POST[members]);
if($_POST[gid]=="")
{
	$groupuid = uniqid();
	$query="INSERT INTO groups (uid,name,author_id) VALUES ('".$groupuid."','".$_POST[name]."','".$_POST[uid]."')";
}
else
{
	$groupuid=$_POST[gid];
	$query="INSERT INTO groups (uid,name,author_id) VALUES ('".$_POST[gid]."','".$_POST[name]."','".$_POST[uid]."')";
}
mysqli_query($con,$query);

//group members
foreach ($members as $member) {
	$query="INSERT INTO group_members (groupuid,memberid) VALUES ('".$groupuid."','".$member."')";
	mysqli_query($con,$query);
}

echo "done";

?>
