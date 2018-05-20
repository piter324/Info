<?php
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

$ids = $_POST['ids'];

if($ids!='')
{
$idarray = explode(',',$ids);
for($i=0;$i<count($idarray);$i++)
{
	$query="DELETE FROM entries WHERE uid='".$idarray[$i]."' OR parent_uid='".$idarray[$i]."'";
	mysqli_query($con,$query);
	$query="DELETE FROM marks WHERE entry_uid='".$idarray[$i]."' OR entry_parent_uid='".$idarray[$i]."'";
	mysqli_query($con,$query);
}
	echo "done";
}
else
{
	echo "Musisz zaznaczyć wpisy/komentarze do usunięcia";
}
?>