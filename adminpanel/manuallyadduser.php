<?php
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");


	$query="INSERT INTO users (username,password,user_class,email) VALUES ('".$_POST['uname']."','".$_POST['upass']."','".$_POST['uclass']."','".$_POST['uemail']."')";
	mysqli_query($con,$query);
	echo "done";
?>