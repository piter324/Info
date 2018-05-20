<?php
if(strlen($_POST[n])<=50)
{
	session_start();
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

	
	$query="SELECT * FROM users WHERE id='".$_POST[i]."'";
$result=mysqli_query($con,$query);
while($row=mysqli_fetch_array($result))
{
	if(hash('sha256',$_POST[o])==$row[password])
	{
		$newpass=hash('sha256',$_POST[n]);
		$queryupdate="UPDATE users SET password='".$newpass."' WHERE id='".$_POST[i]."'";
		mysqli_query($con,$queryupdate);
		
		$queryupdatetime="UPDATE table_updates SET dzien='".date("d")."', miesiac='".date("m")."', rok='".date("Y")."', godzina='".date("H")."', minuta='".date("i")."', sekunda='".date("s")."' WHERE nazwa_tabeli='users'";
		mysqli_query($con,$queryupdatetime);
		echo "done";
	}
	else
	{
		echo "Stare hasło niepoprawne";
	}
}
}
else
{
	echo "Hasło nie może przekraczać 50 znaków!";
}
?>
