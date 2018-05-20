<?php
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");
if($_FILES['filename']['name']!="")
{
//wydzielanie rozszerzenia
$tmpname=explode(".",$_FILES['filename']['name']);
$extension=end($tmpname);

$possibleexts=array("csv");
if(in_array($extension,$possibleexts))
{
	echo "<meta charset='UTF-8'>";
	move_uploaded_file($_FILES['filename']['tmp_name'],'csv/lista.csv');
	$usersi=file('csv/lista.csv');
	for($i=0;$i<count($usersi);$i++)
	{
		$usersinfo=explode($_POST[znakpodzialu],$usersi[$i]);
		echo "<p>".$usersinfo[0]." - ".$usersinfo[1]." - ".$usersinfo[2]." - ".$usersinfo[3]." | GOTOWE</p>";
		$queryadd="INSERT INTO users (username,password,user_class,email) VALUES ('".$usersinfo[0]."','".$usersinfo[1]."','".$usersinfo[2]."','".$usersinfo[3]."')";
		mysqli_query($con,$queryadd);
	}
	echo "<button onclick=\"window.location='index.php'\">Wróć</button>";
	
}
else
{
	echo "<meta charset='UTF-8'><div style='margin:100px auto 0px auto; width:400px; font-family:Trebuchet MS; font-size:24px; text-align:center;'><p style='font-size:100px; color:#FF0000;'>!</p><p>Plik nie spełnia wymogów serwisu</p><p style='font-size:16px'>Plik nie posiada rozszerzenia *.jpg ani *.jpeg</p><a style='font-size:18px; text-decoration:none;' href=\"javascript:window.location.href='main.php'\">Wróć do info.salez</a></div>";
}
}
else
{
	echo "<meta charset='UTF-8'><div style='margin:100px auto 0px auto; width:400px; font-family:Trebuchet MS; font-size:24px; text-align:center;'><p style='font-size:100px; color:#FF0000;'>!</p><p>Żaden plik nie został wybrany</p><a style='font-size:18px; text-decoration:none;' href=\"javascript:window.location.href='main.php'\">Wróć do info.salez</a></div>";
}
?>