<?php
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

$query="SELECT * FROM users WHERE username LIKE '%".$_POST[filter]."%' ORDER BY CHAR_LENGTH(user_class) ASC, user_class ASC";
$result=mysqli_query($con,$query);
$osoby=array();
	while($row=mysqli_fetch_array($result))
	{
		if(!in_array($row[username],$osoby))
			{
				$osoby[]=$row[id];
			}
	}
	$returnik="";
	for($i=0;$i<count($osoby);$i++)
	{
		$returnik.=$osoby[$i].",";
	}
	echo substr($returnik,0,-1);
?>
