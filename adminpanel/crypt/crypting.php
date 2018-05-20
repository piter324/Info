<?php
$con=mysqli_connect("localhost","infosalez_auto","infosalez2413","infosalez");
$con->set_charset("utf8");

$poczatek = $_POST[pocz];
$koniec = $_POST[koniec];
$query = "SELECT * FROM users WHERE id >= ".$poczatek." AND id <= ".$koniec;
$result = mysqli_query($con,$query);
while($row = mysqli_fetch_array($result))
{
	$newpass = sha1($row[password]);
	echo $row[id]." -> ".$row[username]." -> ".$row[password]." -> ".$newpass;
	
	if($_POST[dobazy]==1)
	{
		$query2 = "UPDATE users SET password = '".$newpass."' WHERE id = ".$row[id];
		mysqli_query($con,$query2);
		echo "<b> GOTOWY</b>";
	}
	echo "<br>";
}
?>