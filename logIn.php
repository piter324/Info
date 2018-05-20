<?php
$config_array = parse_ini_file("../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

$query="SELECT * FROM users WHERE email='".$_POST['email']."'";
$result=mysqli_query($con,$query);
$row=mysqli_fetch_array($result);
if(sizeof($row)<1)
{
	echo 3;
}
else
{
	if($row['passwordToBeChanged']=='Y')
		echo 4;
	else
	{
$encryptedpass=hash('sha256',$_POST['password']);
if($row['password']==$encryptedpass)
{
	session_start();
	$_SESSION['userid']=$row['id'];
	$_SESSION['username']=$row['username'];
	$_SESSION['email']=$row['email'];
	$_SESSION['userclass']=$row['user_class'];
	echo 1;
}
else
{
	echo 2;
}
}
}
?>