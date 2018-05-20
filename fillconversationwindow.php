<?php
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");

$queryname="SELECT * FROM users WHERE id='".$_POST[rid]."'";
$resultname=mysqli_query($con,$queryname);
$dostepny=false;
while($rowname=mysqli_fetch_array($resultname))
{
	$nazwarecipientatab=explode(" ",$rowname[username]);
	$nazwarecipienta = $nazwarecipientatab[1]." ".$nazwarecipientatab[0];
	if($rowname[loggedin]=='Y')
	{
		$dostepny=true;
	}
}

echo "<div class='recipientName'><div class='inlinowe' id='nameOfTheRecipient'>".$nazwarecipienta."</div><div class='inlinowe' id='closingx'><img src='images/closingx.png' onclick=\"$('div.messagingCanvas').fadeOut('fast')\"></div></div>
	<div class='conversationWindow'>
	<div class='listedMessages' onmouseover='setToRead();'>
	</div>
	<div class='writingMessage'>
	<textarea class='newMessageText' onclick='setToRead();'></textarea>
	<script>
		$('textarea.newMessageText').keypress(function(e){
			if(e.which==13)
				{
					sendMessage(".$_POST[rid].");
				}
			setToRead();
			});
	</script>
	<p style='text-align:right; margin-top:0px;'><button onclick=\"sendMessage(".$_POST[rid].");\">Wyślij</button>
	</div>
	</div>";
?>
