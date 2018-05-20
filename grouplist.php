<?php
	session_start();
	$config_array = parse_ini_file("../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

$query="SELECT * FROM groups ORDER BY name ASC";
$result=mysqli_query($con,$query);

echo "Filtruj grupy: <select style='max-width:140px;' id='searchfieldgroup' onchange='showDayDetails(".$_POST[day].",".$_POST[month].",".$_POST[year].",true)'><option value=false>wszystkie...</option>";

while($row=mysqli_fetch_array($result))
{
	$idwgrupie=explode(",",$row[members_id]);
	if($_POST[c]=="nauczyciel"||$_POST[c]=="dyrektor"||$_POST[c]=="administrator")
	{
		if($row[uid]==$_POST[nowvalue])
		{$selsel='selected';}
		else
		{$selsel='';}
			echo "<option value='".$row[uid]."' ".$selsel.">".$row[name]."</option>";
	}
	else
	{
		$query_check_user_in_group="SELECT COUNT(id) FROM group_members WHERE memberid=".$_POST['uid']." AND groupuid='".$row['uid']."'";
		$result_0=mysqli_query($con,$query_check_user_in_group);
		$row_0=mysqli_fetch_array($result_0);
		if ($row_0['COUNT(id)']!=0)
		{
			if($row[uid]==$_POST[nowvalue])
			{$selsel='selected';}
			else
			{$selsel='';}
			echo "<option value='".$row[uid]."' ".$selsel.">".$row[name]."</option>";
		}
	}
}
echo "</select>";
?>
