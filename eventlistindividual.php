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

$klasydowpisania = "";

if ($_POST[c]=="nauczyciel" || $_POST[c]=="dyrektor" ||$_POST[c]=="administrator")
{
	
	$query="SELECT DISTINCT events.* FROM events INNER JOIN events_scopes ON events_scopes.event_uid=events.uid WHERE month=".$m." AND year=".$_POST[y]." AND day=".$_POST['d']." AND type='indywidualne' AND (events_scopes.scope=".$_POST['uid']." OR events.author_id=".$_POST[uid].")";
	$result=mysqli_query($con,$query);
	
while($row=mysqli_fetch_array($result))
{
	$mouseevent="onclick='displayEventActionDiv(\"event".$row[uid]."\");'";
	
	$osobydowpisania = "";
	$osobyid=array();

	$query_09="SELECT scope FROM events_scopes WHERE event_uid='".$row['uid']."'";
							$result_09=mysqli_query($con,$query_09);
							while($row_09=mysqli_fetch_array($result_09))
							{
								$osobyid[]=$row_09['scope'];
							}
	$query2="SELECT * FROM users";
	$result2=mysqli_query($con,$query2);
	$osoby=array();
	while($row2=mysqli_fetch_array($result2))
	{
		for($i=0;$i<count($osobyid);$i++)
		{
			if($osobyid[$i]==$row2[id])
			{ $osoby[]=$row2[username]; }
		}
	}
	
	for($i = 0;$i<count($osoby);$i++)
	{
		$osobydowpisania=$osobydowpisania.$osoby[$i].", ";
	}
	$osobydowpisania = substr($osobydowpisania,0,-2);
	
	$query3 = "SELECT * FROM users WHERE id=".$row[author_id];
	$result3 = mysqli_query($con,$query3);
	while($row3 = mysqli_fetch_array($result3))
	{
		$author = $row3[username];
	}
	echo "<div class='eventitselfIndividual' id='event".$row[uid]."' ".$mouseevent.">
	<p class='eventtext'>".$row[text]."</p>
	<p class='eventdetails'>".$row[details]."</p>
	<p class='eventmetadata'><b>Rodzaj:</b> ".$row[subject]."<br><b>Uczestnicy:</b> ".$osobydowpisania."<br><b>Autor:</b> ".$author."<br><b>Dodano:</b> ".$row[added_date]." <b>o</b> ".$row[added_hour]."</p>
	</div>";
}
}
else
{
	$query="SELECT * FROM events WHERE day=\"".$_POST[d]."\" AND month=\"".$m."\" AND year=\"".$_POST[y]."\" AND type='indywidualne'";
	$result=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($result))
	{
		$osobydowpisania = "";
		$osobyid=array();
		$query_09="SELECT scope FROM events_scopes WHERE event_uid='".$row['uid']."'";
							$result_09=mysqli_query($con,$query_09);
							while($row_09=mysqli_fetch_array($result_09))
							{
								$osobyid[]=$row_09['scope'];
							}

		$osoby=array();
		
		if($row[author_id]==$_POST[uid])
			{ $mouseevent="onclick='displayEventActionDiv(\"event".$row[uid]."\");'"; }
			else {$mouseevent="";}
		
		if(in_array($_POST[uid],$osobyid))
		{
			$query2="SELECT * FROM users";
			$result2=mysqli_query($con,$query2);
			while($row2=mysqli_fetch_array($result2))
			{
				for($i=0;$i<count($osobyid);$i++)
				{
					if($osobyid[$i]==$row2[id])
					{ $osoby[]=$row2[username]; }
				}
			}
			
			#region odpowiedni rozkład tekstu
			/*
			if(count($osoby)>2)
			{
				$dwojkowanie=1;
				for($i = 0;$i<count($osoby);$i++)
				{
					if($dwojkowanie<2)
					{
						if($i+1<count($osoby))
						$osobydowpisania=$osobydowpisania.$osoby[$i].", ";
						else
						$osobydowpisania=$osobydowpisania.$osoby[$i];
						
						$dwojkowanie++;
				
					}
					else
					{
						$osobydowpisania=$osobydowpisania.$osoby[$i]."<br>";
						$dwojkowanie=1;
					}
			
				}
			}
			else
			{
				for($i = 0;$i<count($osoby);$i++)
				{
						if($i+1<count($osoby))
						$osobydowpisania=$osobydowpisania.$osoby[$i].", ";
						else
						$osobydowpisania=$osobydowpisania.$osoby[$i];
				}
			}*/
			#endregion
	for($i = 0;$i<count($osoby);$i++)
	{
		$osobydowpisania=$osobydowpisania.$osoby[$i].", ";
	}
	$osobydowpisania = substr($osobydowpisania,0,-2);
			
			$query3 = "SELECT * FROM users WHERE id=".$row[author_id];
			$result3 = mysqli_query($con,$query3);
			while($row3 = mysqli_fetch_array($result3))
			{
				$author = $row3[username];
			}
			echo "<div class='eventitselfIndividual' id='event".$row[uid]."' ".$mouseevent.">
			<p class='eventtext'>".$row[text]."</p>
			<p class='eventdetails'>".$row[details]."</p>
			<p class='eventmetadata'><b>Rodzaj:</b> ".$row[subject]."<br><b>Uczestnicy:</b> ".$osobydowpisania."<br><b>Autor:</b> ".$author."<br><b>Dodano:</b> ".$row[added_date]." <b>o</b> ".$row[added_hour]."</p></div>";
		}
	}
}

?>
