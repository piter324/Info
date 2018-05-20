<?php
echo "<div id='dialogBox'>";
if($_POST['showx']=='true')
{
	echo "<div id='closingx' class='inlinowe' onclick='closeDialog()'><img class='closingxwhite' src='../images/closingxwhite.png'></div>";
}
echo "<div id='dialogBoxTitleBar'><div id='dialogBoxTitle' class='inlinowe'>".$_POST['title']."</div>";
echo "</div><div id='dialogBoxContent'>".$_POST['content']."</div>";
//write buttons like so: name1&function1#name2&function2
if(strlen($_POST['buttons'])>0)
{
	
	$buttons=explode('#',$_POST['buttons']);
	echo "<div id='dialogBoxButtons'>";
	for($i=0;$i<sizeof($buttons);$i++)
	{
		$nameandaction=explode('&', $buttons[$i]);
		echo "<button class='dialogBoxButton' onclick=\"".$nameandaction[1]."\">".$nameandaction[0]."</button>";
	}
	echo "</div>";
}
echo "</div>";
?>