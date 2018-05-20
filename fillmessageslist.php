<?php
		session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");
	
	$query = "SELECT * FROM messages WHERE sender='".$_POST[uid]."' OR recipient='".$_POST[uid]."'";
	$result = mysqli_query($con,$query);
	
	$osobyzktorymipisal=array();
	while($row=mysqli_fetch_array($result))
	{
		if(!in_array($row[sender],$osobyzktorymipisal)&&$row[sender]!=$_POST[uid])
		{
			$osobyzktorymipisal[]=$row[sender];
		}
		if(!in_array($row[recipient],$osobyzktorymipisal)&&$row[recipient]!=$_POST[uid])
		{
			$osobyzktorymipisal[]=$row[recipient];
		}
	}
	for($i=0;$i<count($osobyzktorymipisal);$i++)
	{
		$query2="SELECT * FROM messages WHERE (sender='".$_POST[uid]."' AND recipient='".$osobyzktorymipisal[$i]."') OR (sender='".$osobyzktorymipisal[$i]."' AND recipient='".$_POST[uid]."') ORDER BY id DESC LIMIT 1";
		$result2=mysqli_query($con,$query2);
		while($row2=mysqli_fetch_array($result2))
		{
			$query3="SELECT * FROM users WHERE id='".$osobyzktorymipisal[$i]."'";
			$result3=mysqli_query($con,$query3);
			$nazwa="";
			while($row3=mysqli_fetch_array($result3))
			{
				$nazwatab=explode(" ",$row3[username]);
				$nazwa=$nazwatab[1]." ".$nazwatab[0];
			}
			if($row2[ifread]=='Y')
			{
				$message = $row2[text];
			}
			else
			{
				$message = "<b>".$row2[text]."</b>";
			}
			$emotikonytext=array(":D",":d",":)","-_-",";)",":3",":(",":*","T-T","t-t",":o",":0",":<","<3",":P",":p");
			$emotikonyimages=array("<img class='emoticon' src='images/emoticons/1.png'>",
			"<img class='emoticon' src='images/emoticons/1.png'>",
			"<img class='emoticon' src='images/emoticons/2.png'>",
			"<img class='emoticon' src='images/emoticons/3.png'>",
			"<img class='emoticon' src='images/emoticons/4.png'>",
			"<img class='emoticon' src='images/emoticons/5.png'>",
			"<img class='emoticon' src='images/emoticons/6.png'>",
			"<img class='emoticon' src='images/emoticons/7.png'>",
			"<img class='emoticon' src='images/emoticons/8.png'>",
			"<img class='emoticon' src='images/emoticons/8.png'>",
			"<img class='emoticon' src='images/emoticons/9.png'>",
			"<img class='emoticon' src='images/emoticons/9.png'>",
			"<img class='emoticon' src='images/emoticons/10.png'>",
			"<img class='emoticon' src='images/emoticons/11.png'>",
			"<img class='emoticon' src='images/emoticons/12.png'>",
			"<img class='emoticon' src='images/emoticons/12.png'>");
			
			$message = str_replace($emotikonytext,$emotikonyimages,$message);
			
			if(file_exists("images/profiles/".$osobyzktorymipisal[$i].".jpg")&&$osobyzktorymipisal[$i]!='')
			{
				$profilowe = "images/profiles/".$osobyzktorymipisal[$i].".jpg?dt=".date("His");
			}
			else
			{
				$profilowe = "images/profiles/defaultprofile.png";
			}
			
			echo "<div class='messageFromRecipient' onclick=\"startConversation('".$osobyzktorymipisal[$i]."')\"><div class='recipientPhoto'><img src='".$profilowe."' height=50 width=50></div><div class='recipientInfo'><p class='recipientInfoHeader' style='margin-bottom:5px'>".$nazwa."</p><p class='recipientInfoLastMessage' style='margin-top:0px'>".$message."</p></div></div>";
		}
				
	}
?>
