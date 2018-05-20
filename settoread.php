<?php
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");

$query = "SELECT * FROM messages WHERE ifread='N' AND recipient='".$_POST[sid]."' AND sender='".$_POST[rid]."'";
//echo $query;
$result = mysqli_query($con,$query);

$messagesid=array();
while($row=mysqli_fetch_array($result))
{
		$messagesid[]=$row[id];
}
$updatequery="UPDATE messages SET ifread='Y' WHERE ifread='N' AND recipient='".$_POST[sid]."' AND sender='".$_POST[rid]."'";
mysqli_query($con,$updatequery);

$queryupdatetime="UPDATE table_updates SET dzien='".date("d")."', miesiac='".date("m")."', rok='".date("Y")."', godzina='".date("H")."', minuta='".date("i")."', sekunda='".date("s")."' WHERE nazwa_tabeli='messages'";
mysqli_query($con,$queryupdatetime);

for($i=0;$i<count($messagesid);$i++)
{
	echo $messagesid[$i];
	if($i+1<count($messagesid))
	echo ",";
}
?>
