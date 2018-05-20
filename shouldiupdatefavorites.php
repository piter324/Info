<?php
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");
$query="SELECT * FROM favorites WHERE author='".$_POST[uid]."'";
$result=mysqli_query($con,$query);
while($row=mysqli_fetch_array($result))
{
	$zbazy=$row[modified];
	$zestrony=$_POST[lu];
	
	if(strtotime($zbazy)>strtotime($zestrony))
	{
		echo date('H:i:s');
	}
	else
	{
		echo "no";
	}
}
?>
