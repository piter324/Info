<?php
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");
	
	$query = "SELECT * FROM users WHERE loggedin='Y' AND username LIKE '%".$_POST[filter]."%' ORDER BY username ASC";
	$result=mysqli_query($con,$query);
	
	$queryfavs = "SELECT * FROM favorites WHERE author='".$_POST[uid]."'";
	$resultfavs=mysqli_query($con,$queryfavs);
	
	while($rowfavs=mysqli_fetch_array($resultfavs))
	{
		$favorites=explode(',',$rowfavs[people]);
	}
	
	while($row=mysqli_fetch_array($result))
	{
	if($row[id]!=$_POST[uid])
		{
			$nazwim = explode(" ",$row[username]);
			$identity = $nazwim[1]." ".$nazwim[0];
			if(file_exists("images/profiles/".$row[id].".jpg"))
			{
				$profilowe = "images/profiles/".$row[id].".jpg?dt=".date("His");
			}
			else
			{
				$profilowe = "images/profiles/defaultprofile.png";
			}
			echo "<p class='messengerBeltUser' id='".$row[id]."' onclick='startConversation(\"".$row[id]."\")'><span class='inlinowe'><img src='images/ball_av.png' style='height:12px; margin-bottom:9px;'> </span><span class='inlinowe'><img src='".$profilowe."' style='height:30px; width:30px;'> </span><span class='inlinowe'> ".$identity."</span>";
			
			if(!in_array($row[id],$favorites))
			{
				echo "<span class='inlinowe'> <img height=20 width=20 src='images/favorites_star.png' onclick=\"addToFavorites('".$row[id]."')\"></span>";
			}
			
			echo "</p>";
		}
	}
?>
