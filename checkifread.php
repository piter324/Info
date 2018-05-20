<?php
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");

$query="SELECT id FROM messages WHERE ifread='N'";
$result=mysqli_query($con,$query);

while($row=mysqli_fetch_array($result))
{
	$output.=$row[id].',';
}
echo substr($output,0,-1);
?>
