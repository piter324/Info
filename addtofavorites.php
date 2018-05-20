<?php
$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
$con->set_charset("utf8");

$query="SELECT * FROM favorites WHERE author='".$_POST[uid]."'";
$result=mysqli_query($con,$query);
$ilosc_wierszy=mysqli_num_rows($result);

if($ilosc_wierszy>0)
{
while($row=mysqli_fetch_array($result))
{
	$favoritsy=explode(",",$row[people]);
	
		if($row[people]!="")
		{
			if(!in_array($_POST[idtoadd],$favoritsy))
			{
				$nowefavoritsy=$row[people].",".$_POST[idtoadd];
			}
			
		}
		else
		{
			$nowefavoritsy=$_POST[idtoadd];
		}
	$queryadd="UPDATE favorites SET people='".$nowefavoritsy."', modified='".date("d-m-Y H:i:s")."' WHERE author='".$_POST[uid]."'";
	mysqli_query($con,$queryadd);
}
}
else 
{
	$query2="INSERT INTO favorites (author,people,modified) VALUES ('".$_POST[uid]."','".$_POST[idtoadd]."','".date('d-m-Y H:i:s')."')";
	mysqli_query($con,$query2);
}
echo "done";
?>