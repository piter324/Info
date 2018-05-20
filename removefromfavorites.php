<?php
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");

$query="SELECT * FROM favorites WHERE author='".$_POST[uid]."'";
$result=mysqli_query($con,$query);

while($row=mysqli_fetch_array($result))
{
	$favoritsy=explode(",",$row[people]);
	
	for($i=0;$i<count($favoritsy);$i++)
	{
		if($favoritsy[$i]!=$_POST[idtoremove])
		{
			$output.= $favoritsy[$i].",";
		}
	}
	
	
	$queryremove="UPDATE favorites SET people='".substr($output,0,-1)."', modified='".date("d-m-Y H:i:s")."' WHERE author='".$_POST[uid]."'";
	mysqli_query($con,$queryremove);
	echo "done";
}
?>
