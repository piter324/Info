<?php
	session_start();
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

$possibleexts=array("jpg","jpeg","JPG","JPEG");
if((($_FILES["filename"]["type"] == "image/jpeg")
|| ($_FILES["filename"]["type"] == "image/jpg") 
|| ($_FILES["filename"]["type"] == "image/JPG") 
|| ($_FILES["filename"]["type"] == "image/JPEG"))
&&in_array($extension,$possibleexts))
{
	$newname="../images/profiles/".$_SESSON['userid'].".jpg";
	
	$pic = new Imagick($_FILES['filename']['tmp_name']);//specify name
	$pic->resizeImage(300,300,Imagick::FILTER_LANCZOS,1);
	
	//again might have width and height confused
	$pic->writeImage($newname)//output name
	$pic->destroy();
	unlink($_FILES['filename']['tmp_name']);//deletes image
	$queryupdatetime="UPDATE table_updates SET dzien='".date("d")."', miesiac='".date("m")."', rok='".date("Y")."', godzina='".date("H")."', minuta='".date("i")."', sekunda='".date("s")."' WHERE nazwa_tabeli='users'";
	mysqli_query($con,$queryupdatetime);
	header('Location:index.php');
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
