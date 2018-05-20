<?php
$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_COOKIE[db]);
$con->set_charset("utf8");

$query="SELECT liked_by,disliked_by FROM events WHERE id=".$_POST[eid];
$result=mysqli_query($con,$query);

while($row=mysqli_fetch_array($result))
{
	echo $row[liked_by].'#'.$row[disliked_by];
}
?>