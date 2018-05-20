<?php
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");

$query="UPDATE events SET liked_by='".$_POST[liked]."', disliked_by='".$_POST[disliked]."' WHERE id=".$_POST[eid];
mysqli_query($con,$query);

echo $query;
?>
