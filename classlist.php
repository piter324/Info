<?php
	session_start();
	$config_array = parse_ini_file("../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

$query="SELECT * FROM users ORDER BY CHAR_LENGTH(user_class) ASC, user_class ASC";
$result=mysqli_query($con,$query);

echo "Filtruj klasy: <select id='searchfieldclass' onchange='showDayDetails(".$_POST[day].",".$_POST[month].",".$_POST[year].",true)'><option value=false>wszystkie...</option>";
$klasy=array();
while($row=mysqli_fetch_array($result))
{
	if(!in_array($row[user_class],$klasy)&&$row[user_class]!='nauczyciel'&&$row[user_class]!='dyrektor'&&$row[user_class]!='administrator')
	{
		$klasy[]=$row[user_class];
		if($row[user_class]==$_POST[nowvalue])
		{$selsel='selected';}
		else
		{$selsel='';}
			echo "<option value='".$row[user_class]."' ".$selsel.">".$row[user_class]."</option>";
	}
}
echo "</select>";
?>
