<?php
session_start();
if(file_exists("images/profiles/".$_SESSION['userid'].".jpg"))
{
	$profilowe = "images/profiles/".$_SESSION['userid'].".jpg?dt=".date("His");
}
else
{
	$profilowe = "images/profiles/defaultprofile.png";
}
echo "<div class='inlinowe'><img class='profiloweSmall' id='profiloweUpBelt' src='".$profilowe."' style='' onclick=\"showAddContent('showprofile')\"></div><div class='inlinowe'><span class='welcomeUsername'> Witaj <b>".$_SESSION['username']."</b>!</span><span id='dataimieniny' onmouseover=\"changeDateToNames('do')\"  onmouseout=\"changeDateToNames('undo')\">Dziś jest ";
echo date('d.m.Y');
echo "</span><button class='logOut' onclick='wyloguj();'>Wyloguj!</button></div>";
?>
