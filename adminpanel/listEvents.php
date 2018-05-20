<?php
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

$query="SELECT * FROM events WHERE text LIKE '%".$_POST['filter']."%' OR details LIKE '%".$_POST['filter']."%' ORDER BY id DESC";
$result=mysqli_query($con,$query);
echo "<div id='eventListTable'><div class='useronthelist'><div class='columnHeaderDiv tablecell smallColumn'></div>
<div class='columnHeaderDiv tablecell smallColumn'>ID</div>
<div class='columnHeaderDiv tablecell'>tytuł</div>
<div class='columnHeaderDiv tablecell'>szczegóły</div>
<div class='columnHeaderDiv tablecell'>przedmiot</div>
<div class='columnHeaderDiv tablecell'>typ</div>
<div class='columnHeaderDiv tablecell'>autor</div>
<div class='columnHeaderDiv tablecell'>data</div></div>";
while($row=mysqli_fetch_array($result))
{
	$query2="SELECT * FROM users WHERE id=".$row['author_id'];
	$result2=mysqli_query($con,$query2);
	$row2=mysqli_fetch_array($result2);
	echo "<div class='useronthelist' id='".$row['uid']."'><div class='tablecell'><input type='checkbox' class='listedEvent' value='".$row['uid']."'></div><div class='tablecell'>".$row['id']."</div><div class='tablecell'>".$row['text']."</div><div class='tablecell'>".$row['details']."</div><div class='tablecell'>".$row['subject']."</div><div class='tablecell'>".$row['type']."</div><div class='tablecell'>".$row2['username']."</div><div class='tablecell'>".$row['day'].".".$row['month'].".".$row['year']."</div></div>";
}
echo "</div>";
?>