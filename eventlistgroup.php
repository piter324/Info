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
	$query="SELECT * FROM events WHERE day=\"".$_POST[d]."\" AND month=\"".$m."\" AND year=\"".$_POST[y]."\" AND type='grupowe'";
	$result=mysqli_query($con,$query);
while($row=mysqli_fetch_array($result))
{
	$grupydowpisania = "";
	if($_POST[c]=="nauczyciel")
	{ 
		if($row[author_id]==$_POST[uid])
		$mouseevent="onclick='displayEventActionDiv(\"event".$row[uid]."\");'";
	}
	else { $mouseevent="onclick='displayEventActionDiv(\"event".$row[uid]."\");'"; }
	
	$grupyid=array();
		$query_09="SELECT scope FROM events_scopes WHERE event_uid='".$row['uid']."'";
		$result_09=mysqli_query($con,$query_09);
		while($row_09=mysqli_fetch_array($result_09))
		{
			$grupyid[]=$row_09['scope'];
		}
	$grupynames=array();
	$querygr="SELECT * FROM groups";
	$resultgr=mysqli_query($con,$querygr);
	while($rowgr=mysqli_fetch_array($resultgr))
	{
		if(in_array($rowgr[uid],$grupyid))
		{
			$grupynames[]=$rowgr[name];
		}
	}
		for($i = 0;$i<count($grupynames);$i++)
		{
				if($i+1<count($grupynames))
				$grupydowpisania=$grupydowpisania.$grupynames[$i]."<br>";
				else
				$grupydowpisania=$grupydowpisania.$grupynames[$i];
		}
	
	$query2 = "SELECT * FROM users WHERE id=".$row[author_id];
			$result2 = mysqli_query($con,$query2);
			while($row2 = mysqli_fetch_array($result2))
			{
				$author = $row2[username];
			}
	
	if($_POST[f]=='false')
	{
		echo "<div class='eventitselfGroup' id='event".$row[uid]."'".$mouseevent.">
		<p class='eventtext'>".$row[text]."</p>
		<p class='eventdetails'>".$row[details]."</p>
		<p class='eventmetadata'><b>Rodzaj:</b> ".$row[subject]."<br><b>Grupy:</b> ".$grupydowpisania."<br><b>Autor:</b> ".$author."<br><b>Dodano:</b> ".$row[added_date]." <b>o</b> ".$row[added_hour]."</p>
		</div>";
	}
	else
	{
		if(in_array($_POST[f],$grupyid))
		{
			echo "<div class='eventitselfGroup' id='event".$row[uid]."'".$mouseevent.">
			<p class='eventtext'>".$row[text]."</p>
			<p class='eventdetails'>".$row[details]."</p>
			<p class='eventmetadata'><b>Rodzaj:</b> ".$row[subject]."<br><b>Grupy:</b> ".$grupydowpisania."<br><b>Autor:</b> ".$author."<br><b>Dodano:</b> ".$row[added_date]." <b>o</b> ".$row[added_hour]."</p>
			</div>";
		}
	}
}
}
else
{
	$querygr="SELECT groupuid FROM group_members WHERE memberid=".$_POST['uid'];
	$resultgr=mysqli_query($con,$querygr);
	$grupyusera=array();
	while($rowgr=mysqli_fetch_array($resultgr))
	{
		$grupyusera[]=$rowgr['groupuid'];
	}
	
	$query="SELECT * FROM events WHERE day=\"".$_POST[d]."\" AND month=\"".$m."\" AND year=\"".$_POST[y]."\" AND type='grupowe'";
	$result=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($result))
	{
		$grupydowpisania = "";
		
		$grupyid=array();
		$query_09="SELECT scope FROM events_scopes WHERE event_uid='".$row['uid']."'";
		$result_09=mysqli_query($con,$query_09);
		while($row_09=mysqli_fetch_array($result_09))
		{
			$grupyid[]=$row_09['scope'];
		}
		
		$grupynames=array();
		
		if(array_intersect($grupyid,$grupyusera))
		{
			if($row[author_id]==$_POST[uid])
			{ $mouseevent="onclick='displayEventActionDiv(\"event".$row['uid']."\");'"; }
			else {$mouseevent="";}
			
	$querygr="SELECT * FROM groups";
	$resultgr=mysqli_query($con,$querygr);
	while($rowgr=mysqli_fetch_array($resultgr))
	{
		if(in_array($rowgr[uid],$grupyid))
		{
			$grupynames[]=$rowgr[name];
		}
	}
	
		for($i = 0;$i<count($grupynames);$i++)
		{
				if($i+1<count($grupynames))
				$grupydowpisania=$grupydowpisania.$grupynames[$i].",<br>";
				else
				$grupydowpisania=$grupydowpisania.$grupynames[$i];
		}
	
	$query2 = "SELECT * FROM users WHERE id=".$row[author_id];
			$result2 = mysqli_query($con,$query2);
			while($row2 = mysqli_fetch_array($result2))
			{
				$author = $row2[username];
			}
			
	if($_POST[f]=='false')
	{
		echo "<div class='eventitselfGroup' id='event".$row[uid]."'".$mouseevent.">
		<p class='eventtext'>".$row[text]."</p>
		<p class='eventdetails'>".$row[details]."</p>
		<p class='eventmetadata'><b>Rodzaj:</b> ".$row[subject]."<br><b>Grupy:</b> ".$grupydowpisania."<br><b>Autor:</b> ".$author."<br><b>Dodano:</b> ".$row[added_date]." <b>o</b> ".$row[added_hour]."</p>
		</div>";
	}
	else
	{
		if(in_array($_POST[f],$grupyid))
		{
			echo "<div class='eventitselfGroup' id='event".$row[uid]."'".$mouseevent.">
			<p class='eventtext'>".$row[text]."</p>
			<p class='eventdetails'>".$row[details]."</p>
			<p class='eventmetadata'><b>Rodzaj:</b> ".$row[subject]."<br><b>Grupy:</b> ".$grupydowpisania."<br><b>Autor:</b> ".$author."<br><b>Dodano:</b> ".$row[added_date]." <b>o</b> ".$row[added_hour]."</p>
			</div>";
		}
	}
		}
	}
}

?>
