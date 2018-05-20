<?php
//cały ten kod jest w main.php

	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");

$query="SELECT id,username FROM users WHERE user_class='nauczyciel' OR user_class='dyrektor' OR user_class='administrator' ORDER BY user_class DESC";
$result=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($result))
	{
		$output.="{label:".$row[username].",value:".$row[id]."},";
	}
	echo substr($output,0,-1);
	
?>
