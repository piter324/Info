<?php
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");

$query="SELECT * FROM messages WHERE sender='".$_POST['sid']."' OR recipient='".$_POST['sid']."' ORDER BY id ASC";
$result=mysqli_query($con,$query);

$samesender=false;
$samerecipient=false;

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

while($row=mysqli_fetch_array($result))
{
	$tekst = str_replace($emotikonytext,$emotikonyimages,$row[text]);
	
	if($row[sender]==$_POST['sid']&&$row[recipient]==$_POST['rid'])
	{
		
		if($row[id]>$_POST['hmid'])
		{
		echo "<div class='sentMessage' id='".$row[id]."' title='wysłano dnia: ".$row[date_when_sent]." o godzinie: ".$row[hour_when_sent]."'>
		<p><span class='";
		
		if($row[ifread]=='N')
			echo "unreadMessageText";
		else
			echo "readMessageText";
			
		echo "' id='txt".$row[id]."' style='vertical-align:middle;'>".$tekst."</span>";
		if($samesender!=true)
		{
			if(file_exists("images/profiles/".$row[sender].".jpg"))
			{
				$profilowe = "images/profiles/".$row[sender].".jpg?dt=".date("His");
			}
			else
			{
				$profilowe = "images/profiles/defaultprofile.png";
			}
			echo "<img src='".$profilowe."' style='vertical-align:middle; height:25px; width:25px; margin-left:10px;'>";
		}
		else
		{
			echo "<span style='margin-left:35px;'></span>";
		}
		echo "</p>
		</div>";
		}
		$samesender=true;
		$samerecipient=false;
		
	}
	else if($row[recipient]==$_POST['sid']&&$row[sender]==$_POST['rid'])
	{
		if($row[id]>$_POST['hmid'])
		{
		echo "<div class='receivedMessage' id='".$row[id]."' title='wysłano dnia: ".$row[date_when_sent]." o godzinie: ".$row[hour_when_sent]."'>
		<p>";
		if($samerecipient!=true)
		{
			if(file_exists("images/profiles/".$row[sender].".jpg"))
			{
				$profilowe = "images/profiles/".$row[sender].".jpg?dt=".date("His");
			}
			else
			{
				$profilowe = "images/profiles/defaultprofile.png";
			}
			
			echo "<img src='".$profilowe."' style='vertical-align:middle; height:25px; width:25px; margin-right:10px;'>";
		}
		else
		{
			echo "<span style='margin-right:35px;'></span>";
		}
		
		echo "<span class='";
		
		if($row[ifread]=='N')
			echo "unreadMessageText";
		else
			echo "readMessageText";
		
		echo "' id='txt".$row[id]."' style='vertical-align:middle;'>".$tekst."</span></p>
		</div>";
		}
		
		$samesender=false;
		$samerecipient=true;
	}
	
}
?>
