<?php
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

$query="SELECT * FROM entries WHERE parent_uid='' AND content LIKE '%".$_POST['filter']."%' ORDER BY id ASC";
$result=mysqli_query($con,$query);
echo "<div id='adveListTable'><div class='useronthelist'><div class='columnHeaderDiv tablecell smallColumn'></div>
<div class='columnHeaderDiv tablecell smallColumn'>ID</div>
<div class='columnHeaderDiv tablecell'>uid</div>
<div class='columnHeaderDiv tablecell'>autor</div>
<div class='columnHeaderDiv tablecell'>zawartość</div>
<div class='columnHeaderDiv tablecell'>odbiorca</div>
<div class='columnHeaderDiv tablecell'>link</div>
<div class='columnHeaderDiv tablecell'></div></div>";
while($row=mysqli_fetch_array($result))
{
	$query2="SELECT * FROM users WHERE id=".$row['author'];
	$result2=mysqli_query($con,$query2);
	$row2=mysqli_fetch_array($result2);
	echo "<div class='useronthelist' id='".$row['id']."'><div class='tablecell'><input type='checkbox' class='listedEntry' value='".$row['uid']."'></div><div class='tablecell'>".$row['id']."</div><div class='tablecell'>".$row['uid']."</div><div class='tablecell'>".$row2['username']."</div><div class='tablecell'>".$row['content']."</div><div class='tablecell'>";
	if(substr($row['scope'],0,2)=='cl')
	{
		echo substr($row['scope'],2);
	}
	else
	{
		$query3="SELECT * FROM groups WHERE uid='".substr($row['scope'],2)."'";
		$result3=mysqli_query($con,$query3);
		$row3=mysqli_fetch_array($result3);
		echo $row3['name'];
	}

	echo "</div><div class='tablecell'>";

	if($row['link']!='')
	echo "<a href='".$row['link']."' target='_blank'>przejdź</a>";

	echo "</div><div class='tablecell'><button onclick=\"listComments('".$row['uid']."')\">pokaż<br/>komentarze</button></div></div>";
}
echo "</div>";

?>