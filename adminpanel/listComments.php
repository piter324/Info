<?php
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

$query="SELECT * FROM entries WHERE parent_uid='".$_POST['parentuid']."' ORDER BY id DESC";
$result=mysqli_query($con,$query);
if(count(mysqli_fetch_array($result))>0)
{
echo "<div id='adveListTable'><div class='useronthelist'><div class='columnHeaderDiv tablecell smallColumn'></div>
<div class='columnHeaderDiv tablecell smallColumn'>ID</div>
<div class='columnHeaderDiv tablecell'>uid</div>
<div class='columnHeaderDiv tablecell'>autor</div>
<div class='columnHeaderDiv tablecell'>zawartość</div>
<div class='columnHeaderDiv tablecell'>link</div></div>";
$result=mysqli_query($con,$query);
while($row=mysqli_fetch_array($result))
{
	$query2="SELECT * FROM users WHERE id=".$row['author'];
	$result2=mysqli_query($con,$query2);
	$row2=mysqli_fetch_array($result2);
	echo "<div class='useronthelist' id='".$row['id']."'><div class='tablecell'><input type='checkbox' class='listedComment' value='".$row['uid']."'></div><div class='tablecell'>".$row['id']."</div><div class='tablecell'>".$row['uid']."</div><div class='tablecell'>".$row2['username']."</div><div class='tablecell'>".$row['content']."</div><div class='tablecell'>";

	if($row['link']!='')
	echo "<a href='".$row['link']."' target='_blank'>przejdź</a>";

	echo "</div></div>";
}
echo "</div><button onclick=\"$('input:checkbox:visible.listedComment').prop('checked',true);\">Zaznacz widoczne</button><button onclick=\"$('input:checkbox:visible.listedComment').prop('checked',false);\">Odznacz widoczne</button><br />
		<button onclick=\"removeComment()\">Usuń zaznaczone komentarze</button>
		<div id='outputRemoveComment'></div>";
}
else
{
	echo "(brak komentarzy do wybranego wpisu)";
}
?>