<?php
	session_start();
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

	$query="SELECT * FROM adve WHERE author=".$_POST['id'];
	$result=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($result))
	{
		if($row['isOn']=='N')
		{
			$toInput='Off';
			$toInput2=0;
		}
		else
		{
			$toInput='On';
			$toInput2=1;
		}

		echo "<div class='yourAdve' uid='sdfte45td'>
		<img class='adveSwitch' state=".$toInput2." id='adveSwitch".$row['uid']."' onclick=\"toggleAdve('".$row['uid']."')\" src='../images/adve/Switch ".$toInput."-100.png'><span class='adveListText'>".$row['title']."<span class='adveActions'>(<span onclick=\"removeAdve('".$row['uid']."')\">usuń</span>)</span>
		</span>
		</div>";
	}
?>