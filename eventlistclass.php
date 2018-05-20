<?php
	session_start();
	$config_array = parse_ini_file("../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

if ($_POST[m]<10)
{ $m="0".$_POST[m]; }
else
{ $m=$_POST[m]; }


if ($_POST[c]=="nauczyciel" || $_POST[c]=="dyrektor" ||$_POST[c]=="administrator")
{
	$query="SELECT * FROM events WHERE day=\"".$_POST[d]."\" AND month=\"".$m."\" AND year=\"".$_POST[y]."\" AND type='klasowe'";
	$result=mysqli_query($con,$query);
while($row=mysqli_fetch_array($result))
{
	$klasydowpisania = "";
	if($_POST[c]=="nauczyciel")
	{ 
		if($row[author_id]==$_POST[uid])
		$mouseevent="onclick='displayEventActionDiv(\"event".$row[uid]."\");'";
	}
	else { $mouseevent="onclick='displayEventActionDiv(\"event".$row[uid]."\");'"; }
	
	$klasy=array();
		$query_09="SELECT scope FROM events_scopes WHERE event_uid='".$row['uid']."'";
							$result_09=mysqli_query($con,$query_09);
							while($row_09=mysqli_fetch_array($result_09))
							{
								$klasy[]=$row_09['scope'];
							}
	
	#region odpowiedni rozkład tekstu
	if(count($klasy)>4)
	{
		$czworkowanie=1;
		for($i = 0;$i<count($klasy);$i++)
		{
			
			if($czworkowanie<4)
			{
				if($i+1<count($klasy))
				$klasydowpisania=$klasydowpisania.$klasy[$i].", ";
				else
				$klasydowpisania=$klasydowpisania.$klasy[$i];
				$czworkowanie++;
				
			}
			else
			{
				$klasydowpisania=$klasydowpisania.$klasy[$i]."<br>";
				$czworkowanie=1;
			}
			
		}
	}
	else
	{
		for($i = 0;$i<count($klasy);$i++)
		{
				if($i+1<count($klasy))
				$klasydowpisania=$klasydowpisania.$klasy[$i].", ";
				else
				$klasydowpisania=$klasydowpisania.$klasy[$i];
		}
	}
	#endregion
	
	$query2 = "SELECT * FROM users WHERE id=".$row[author_id];
			$result2 = mysqli_query($con,$query2);
			while($row2 = mysqli_fetch_array($result2))
			{
				$author = $row2[username];
			}
	if($_POST[f]=='false')
	{
		echo "<div class='eventitselfClass' id='event".$row[uid]."'".$mouseevent.">
		<p class='eventtext'>".$row[text]."</p>
		<p class='eventdetails'>".$row[details]."</p>
		<p class='eventmetadata'><b>Rodzaj:</b> ".$row[subject]."<br><b>Klasy:</b> ".$klasydowpisania."<br><b>Autor:</b> ".$author."<br><b>Dodano:</b> ".$row[added_date]." <b>o</b> ".$row[added_hour]."</p>
		</div>";
	}
	else
	{
		if(in_array($_POST[f],$klasy))
		{
			echo "<div class='eventitselfClass' id='event".$row[uid]."'".$mouseevent.">
			<p class='eventtext'>".$row[text]."</p>
			<p class='eventdetails'>".$row[details]."</p>
			<p class='eventmetadata'><b>Rodzaj:</b> ".$row[subject]."<br><b>Klasy:</b> ".$klasydowpisania."<br><b>Autor:</b> ".$author."<br><b>Dodano:</b> ".$row[added_date]." <b>o</b> ".$row[added_hour]."</p>
			</div>";
		}
	}
}
}
else
{
	$query="SELECT * FROM events WHERE day=\"".$_POST['d']."\" AND month=\"".$m."\" AND year=\"".$_POST['y']."\" AND type='klasowe'";
	$result=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($result))
	{
		$klasydowpisania = "";
		$klasy=array();
		$query_09="SELECT scope FROM events_scopes WHERE event_uid='".$row['uid']."'";
							$result_09=mysqli_query($con,$query_09);
							while($row_09=mysqli_fetch_array($result_09))
							{
								$klasy[]=$row_09['scope'];
							}


		if(in_array($_POST[c],$klasy))
		{
			if($row[author_id]==$_POST[uid])
			{ $mouseevent="onclick='displayEventActionDiv(\"event".$row['uid']."\");'"; }
			else {$mouseevent="";}
			
			#region odpowiedni rozkład tekstu
			if(count($klasy)>4)
			{
				$czworkowanie=1;
				for($i = 0;$i<count($klasy);$i++)
				{
					if($czworkowanie<4)
					{
						if($i+1<count($klasy))
						$klasydowpisania=$klasydowpisania.$klasy[$i].", ";
						else
						$klasydowpisania=$klasydowpisania.$klasy[$i];
						
						$czworkowanie++;
				
					}
					else
					{
						$klasydowpisania=$klasydowpisania.$klasy[$i]."<br>";
						$czworkowanie=1;
					}
			
				}
			}
			else
			{
				for($i = 0;$i<count($klasy);$i++)
				{
						if($i+1<count($klasy))
						$klasydowpisania=$klasydowpisania.$klasy[$i].", ";
						else
						$klasydowpisania=$klasydowpisania.$klasy[$i];
				}
			}
			#endregion
			
			$query2 = "SELECT * FROM users WHERE id=".$row[author_id];
			$result2 = mysqli_query($con,$query2);
			while($row2 = mysqli_fetch_array($result2))
			{
				$author = $row2['username'];
			}
			
			echo "<div class='eventitselfClass' id='event".$row['uid']."' ".$mouseevent.">
			<p class='eventtext'>".$row['text']."</p>
			<p class='eventdetails'>".$row['details']."</p>
			<p class='eventmetadata'><b>Rodzaj:</b> ".$row['subject']."<br><b>Klasy:</b> ".$klasydowpisania."<br><b>Autor:</b> ".$author."<br><b>Dodano:</b> ".$row['added_date']." <b>o</b> ".$row['added_hour']."</p>
			</div>";
		}
	}
}

?>
