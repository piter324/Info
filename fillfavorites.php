<?php
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");

$query="SELECT * FROM favorites WHERE author='".$_POST[uid]."'";
$result=mysqli_query($con,$query);
while($row=mysqli_fetch_array($result))
{
	$ludzie=explode(',',$row[people]);
	for($i=0;$i<count($ludzie);$i++)
	{
		$querypeople="SELECT id,username,loggedin FROM users WHERE id='".$ludzie[$i]."'";
		$resultpeople=mysqli_query($con,$querypeople);
		while($rowpeople=mysqli_fetch_array($resultpeople))
		{
			$nazwim = explode(" ",$rowpeople[username]);
			$identity = $nazwim[1]." ".$nazwim[0];
			if($rowpeople[loggedin]=='Y'){ $avunav='ball_av'; }
			else if($rowpeople[loggedin]=='N'){ $avunav='ball_unav'; }
			if(file_exists("images/profiles/".$rowpeople[id].".jpg"))
			{
				$profilowe = "images/profiles/".$rowpeople[id].".jpg?dt=".date("His");
			}
			else
			{
				$profilowe = "images/profiles/defaultprofile.png";
			}
			echo "<p class='messengerBeltUser' id='".$rowpeople[id]."' onclick='startConversation(\"".$rowpeople[id]."\")'><span class='inlinowe'><img src='images/".$avunav.".png' style='height:12px; margin-bottom:9px;'> </span><span class='inlinowe'><img src='".$profilowe."' style='height:30px; width:30px;'> </span><span class='inlinowe'> ".$identity."</span><span class='deletingFromFavorites' onclick='removeFromFavorites(\"".$rowpeople[id]."\")'><img src='images/minus.png'></span></p>";
		}
	}
	
}
?>
