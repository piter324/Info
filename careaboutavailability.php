<?php
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");

$query="SELECT * FROM users WHERE id=\"".$_POST[uid]."\"";
$result=mysqli_query($con,$query);
while($row=mysqli_fetch_array($result))
{
	if($row[loggedin]!='Y')
	{
		$query2="UPDATE users SET loggedin='Y' WHERE id=".$_POST[uid];
		mysqli_query($con,$query2);
		
		$queryupdatetime="UPDATE table_updates SET dzien='".date("d")."', miesiac='".date("m")."', rok='".date("Y")."', godzina='".date("H")."', minuta='".date("i")."', sekunda='".date("s")."' WHERE nazwa_tabeli='users'";
		mysqli_query($con,$queryupdatetime);
	}
}
?>
