<?php
	session_start();
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
		die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

		$query2="SELECT COUNT(id) FROM marks WHERE entry_uid='".$_POST['uid']."' AND type='plus'";
		$result2=mysqli_query($con,$query2);
		$row2=mysqli_fetch_array($result2);
		$ilplusow=$row2['COUNT(id)'];
		$query2="SELECT COUNT(id) FROM marks WHERE entry_uid='".$_POST['uid']."' AND type='minus'";
		$result2=mysqli_query($con,$query2);
		$row2=mysqli_fetch_array($result2);
		$ilminusow=intval($row2['COUNT(id)'])*(-1);
		$result=$ilminusow+$ilplusow;
		echo $result;
?>