<?php
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

$ids = $_POST[ids];

if($ids!='')
{
$idarray = explode(',',$ids);
for($i=0;$i<count($idarray);$i++)
{
	$query="DELETE FROM events WHERE uid='".$idarray[$i]."'";
	mysqli_query($con,$query);
	$query="DELETE FROM events_scopes WHERE event_uid='".$idarray[$i]."'";
	mysqli_query($con,$query);
}
	echo "done";
}
else
{
	echo "Musisz zaznaczyć ogłoszenia do usunięcia";
}
?>