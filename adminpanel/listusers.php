<?php
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

$query="SELECT * FROM users ORDER BY CHAR_LENGTH(user_class) ASC, user_class ASC";
$result=mysqli_query($con,$query);
echo "<div id='userListTable'><div class='useronthelist'><div class='columnHeaderDiv tablecell smallColumn'></div>
<div class='columnHeaderDiv tablecell smallColumn'>ID</div><div class='columnHeaderDiv tablecell'>nazwa użytkownika</div><div class='columnHeaderDiv tablecell'>klasa</div><div class='columnHeaderDiv tablecell'>e-mail</div><div class='columnHeaderDiv tablecell'>hasło</div></div>";
while($row=mysqli_fetch_array($result))
{
	echo "<div class='useronthelist' id='".$row[id]."'><div class='tablecell'><input type='checkbox' class='listedUser' value='".$row[id]."'></div><div class='tablecell'>".$row[id]."</div><div class='tablecell'>".$row[username]."</div><div class='tablecell'>".$row[user_class]."</div><div class='tablecell'>".$row[email]."</div><div class='tablecell'>";
	if($row['passwordToBeChanged']=='Y')
		echo "hasło początkowe: ".$row['password'];
	else
		echo "hasło zostało już zmienione";

	echo "</div></div>";
}
echo "</div>";
?>