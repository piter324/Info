<?php
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");
$query="SELECT * FROM messages WHERE sender='".$_POST[uid]."' OR recipient='".$_POST[uid]."' ORDER BY id DESC LIMIT 1";
$result=mysqli_query($con,$query);
while($row=mysqli_fetch_array($result))
{
	if($_POST[li]<$row[id] || $_POST[rd]!=$row[ifread])
	{
		echo $row[id].'#'.$row[ifread];
	}
	else
	{
		echo "no";
	}
}
?>
