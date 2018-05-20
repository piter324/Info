<?php
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

$ids = $_POST[ids];
$newname = $_POST[newname];
$newclass = $_POST[newclass];
$idarray = explode(',',$ids);

if($ids!='')
{
for($i=0;$i<count($idarray);$i++)
{
	if($newname=="")
	{
		$query="UPDATE users SET user_class='".$newclass."' WHERE id='".$idarray[$i]."'";
	}
	else if($newclass=="")
	{
		$query="UPDATE users SET username='".$newname."' WHERE id='".$idarray[$i]."'";
	}
	else
	{
		$query="UPDATE users SET username='".$newname."',user_class='".$newclass."' WHERE id='".$idarray[$i]."'";
	}
	mysqli_query($con,$query);
	
		echo "done";
	
}
}
else
{
	echo "Musisz zaznaczyć użytkowników objętych zmianami";
}
?>