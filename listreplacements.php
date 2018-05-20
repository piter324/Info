<?php
	session_start();
	$config_array = parse_ini_file("../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

if($_POST[m]<10){$m="0".$_POST[m];}
else{$m=$_POST[m];}

$replaced='';
$replacing='';

$date = $_POST['y']."-".$m."-".$_POST['d'];
$query="SELECT * FROM replacements WHERE date='".$date."' ORDER BY lesson ASC";
$result=mysqli_query($con,$query);

while($row=mysqli_fetch_array($result))
{
	$replaced='';
	$replacing='';
	if($_POST[c]!='nauczyciel'&&$_POST[c]!='dyrektor'&&$_POST[c]!='administrator')
	{
			if($row[scopetype]=='klasowe'&&$row[scope]==$_POST[c])
			{
				$hidden="";
			}
			else if($row[scopetype]=='klasowe')
			{
				$hidden="style='display:none'";
			}

			if($row[scopetype]=='grupowe')
			{
			$querygr="SELECT * FROM groups WHERE uid='".$row[scope]."'";
			$resultgr=mysqli_query($con,$querygr);
			while($rowgr=mysqli_fetch_array($resultgr))
			{
				if($rowgr[members_id]!="")
				{
					$userzywgrupie=array();
					$query_08="SELECT * FROM group_members WHERE groupuid='".$rowgr[uid]."'";
					$result_08=mysqli_query($con,$query_08);
					while($row_08=mysqli_fetch_array($result_08))
					{
						$userzywgrupie[]=$row_08['memberid'];
					}

					if(in_array($_POST[uid],$userzywgrupie))
					{
						$hidden="";
					}
					else
					{
						$hidden="style='display:none'";
					}
				}
			}
		}
	}
	

	echo "<div class='replacementItself' ".$hidden.">
	<div class='lessonNumber'>".$row[lesson]."</div>
	<div class='roomNumber'>".$row[room]."</div>
	<div class='replaced'><div class='inlinowe leftOne'><img class='profiloweSmall circularPhoto' src='images/profiles/";
	
	if(file_exists('images/profiles/'.$row[replaced].'.jpg'))
	{	echo $row[replaced].".jpg";	}
	else
	{	echo "defaultprofile.png";	}
	
	echo "'></div><div class='inlinowe'>";
	
	$querynames="SELECT * FROM users WHERE id='".$row[replaced]."'";
	$resultnames=mysqli_query($con,$querynames);
	while($rownames=mysqli_fetch_array($resultnames))
	{
		$replaced = $rownames[username];
		echo $rownames[username];
	}
	if($replaced=='')
	{
		echo $row[replaced];
	}
	
	echo "</div></div><div class='replacing'><div class='inlinowe leftOne'>";
	
	if($row[replacing]!='klasa/grupa zwolniona')
	{
		echo "<img class='profiloweSmall circularPhoto' src='images/profiles/";
		if(file_exists('images/profiles/'.$row[replacing].'.jpg'))
		{	echo $row[replacing].".jpg";	}
		else
		{	echo "defaultprofile.png";	}
	
		echo "'>";
	
	
	echo "</div><div class='inlinowe'>";
	
	$querynames="SELECT * FROM users WHERE id='".$row[replacing]."'";
	$resultnames=mysqli_query($con,$querynames);
	while($rownames=mysqli_fetch_array($resultnames))
	{
		$replacing = $rownames[username];
		echo $rownames[username];
	}
	}
	else
	{
		echo "<img class='profiloweSmall' src='images/nolessonx.png'></div><div class='inlinowe'>";
	}
	if($replacing=='')
	{
		echo $row[replacing];
	}
	
	echo "</div></div>
	<div class='replacementScope'>";

	if($row['scopetype']=='grupowe')
	{
		$query_12="SELECT name FROM groups WHERE uid='".$row['scope']."'";
		$result_12=mysqli_query($con,$query_12);
		$row_12=mysqli_fetch_array($result_12);
		echo $row_12['name'];
	}
	else
		echo $row[scope];

	echo "</div>
	<div class='replacementSubject'>".$row[subject]."</div>";

if($_POST[c]=='dyrektor'||$_POST[c]=='administrator')
{
	echo "<div class='replacementEditDelete'><div class='replacementAction' onclick='showAddContent(\"createormodifyreplacement\",\"".$row[id]."\");'><img class='editdeleteSmall' src='images/edit-black.png'> edytuj</div><div class='replacementAction' onclick='deleteReplacement(\"".$row[id]."\",true);'><img class='editdeleteSmall' src='images/delete-black.png'> usuń</div></div>";
}
echo "</div>";
}
?>
