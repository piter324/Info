<?php
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

$ids = $_POST[ids];
$idarray = explode(',',$ids);

echo "<center><table><tr><td class='columnHeader'>ID</td><td class='columnHeader'>Nazwa użytkownika</td><td class='columnHeader'>Hasło</td></tr>";
for($i=0;$i<count($idarray);$i++)
{

	$query = "UPDATE users SET password='".$_POST[newpass]."', passwordToBeChanged='Y' WHERE id='".$idarray[$i]."'";
	mysqli_query($con,$query);

	$queryshow = "SELECT * FROM users WHERE id='".$idarray[$i]."'";
	$resultshow = mysqli_query($con,$queryshow);
	while($rowshow=mysqli_fetch_array($resultshow))
	{
		echo "<tr><td>".$rowshow[id]."</td><td>".$rowshow[username]."</td><td>".$_POST[newpass]."</td></tr>";
	}
}
echo "</table></center>";
?>