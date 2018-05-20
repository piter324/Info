<?php
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

$query="SELECT * FROM adve WHERE title LIKE '%".$_POST['filter']."%' OR content LIKE '%".$_POST['filter']."%' ORDER BY title ASC";
$result=mysqli_query($con,$query);
echo "<div id='adveListTable'><div class='useronthelist'><div class='columnHeaderDiv tablecell smallColumn'></div>
<div class='columnHeaderDiv tablecell smallColumn'>ID</div>
<div class='columnHeaderDiv tablecell'>uid</div>
<div class='columnHeaderDiv tablecell'>tytuł</div>
<div class='columnHeaderDiv tablecell'>zawartość</div>
<div class='columnHeaderDiv tablecell'>typ</div>
<div class='columnHeaderDiv tablecell'>autor</div></div>";
while($row=mysqli_fetch_array($result))
{
	$query2="SELECT * FROM users WHERE id=".$row['author'];
	$result2=mysqli_query($con,$query2);
	$row2=mysqli_fetch_array($result2);
	echo "<div class='useronthelist' id='".$row['uid']."'><div class='tablecell'><input type='checkbox' class='listedAdve' value='".$row['uid']."'></div><div class='tablecell'>".$row['id']."</div><div class='tablecell'>".$row['uid']."</div><div class='tablecell'>".$row['title']."</div><div class='tablecell'>".$row['content']."</div><div class='tablecell'>".$row['scopetype']."</div><div class='tablecell'>".$row2['username']."</div></div>";
}
echo "</div>";
?>