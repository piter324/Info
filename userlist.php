<?php
echo "<meta charset='UTF-8'>";
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");
$query="SELECT * FROM users";
$result=mysqli_query($con,$query);
while($row=mysqli_fetch_array($result))
{
	echo $row[id]." - ".$row[username]." - ".$row[password]."<br>";
}
?>
