<?php
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

$query="SELECT * FROM users WHERE username LIKE '%".$_POST[filter]."%'";
$result=mysqli_query($con,$query);
while($row=mysqli_fetch_array($result))
{
	$output.= $row[id].',';
}
echo substr($output,0,-1);
?>