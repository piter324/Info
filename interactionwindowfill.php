<?php
session_start();
$config_array = parse_ini_file("../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

//zmienne przechowujace tresc pol formularza
$tytul="";
$szczegoly="";
$przedmiot="";
$dzien="";
$miesiac="";
$rok="";
$checkedstate="";
$funkcjaButtonaGotowe="addEvent(true,'klasowe')";
$header = "Dodaj wydarzenie";

if($_POST[t]=="createevent"||$_POST[t]=="modifyevent")
{

//zmienne wypelniane tylko w razie modyfikacji
if($_POST[t]=="modifyevent")
{
	$query2="SELECT * FROM events WHERE uid='".$_POST[i]."'";
	$result2=mysqli_query($con,$query2);
	$row2=mysqli_fetch_array($result2);
	
		$tytul=$row2[text];
		$szczegoly=$row2[details];
		$przedmiot=$row2[subject];
		$dzien=$row2[day];
		$miesiac=$row2[month];
		$rok=$row2[year];
	
	$funkcjaButtonaGotowe="modifyEvent()";
	$header = "Modyfikuj wydarzenie";
}
else
{
	$rok = date('Y');
}


echo "<p class='interactionWindowHeader'>".$header."</p>
<p class='addEventElement'><b>Tytuł:</b> <input type='text' id='text' maxlength='100' value='".$tytul."'/> (max. 100 znaków)</p>
<p class='addEventElement'><b>Szczegóły:</b> (max.320 znaków):<br><textarea style='resize:none' maxlength='320' id='eventdetails'>".$szczegoly."</textarea></p>
<p class='addEventElement'><b>Rodzaj:</b> <input type='text' id='subject' value='".$przedmiot."' /></p>
<center><div class='addEventCoveredClasses'>";
if($_POST[st]=="indywidualne")
{
	echo "<p><b>Osoby objęte wydarzeniem:</b></p>
	<p>Filtruj: <input type='text' id='peopleFilterTB' onkeyup=\"filterPersons()\"></p><div class='addEventCoveredClassesList'>";
	$funkcjaButtonaGotowe="addEvent(true,'indywidualne')";
	$scopetype="indywidualne";
}
else if($_POST[st]=="klasowe")
{
	echo "<p><b>Klasy objęte wydarzeniem:</b></p><p>";
	$funkcjaButtonaGotowe="addEvent(true,'klasowe')";
	$scopetype="klasowe";
}
else if($_POST[st]=="grupowe")
{
	echo "<p><b>Grupy objęte wydarzeniem:</b></p><p>";
	$funkcjaButtonaGotowe="addEvent(true,'grupowe')";
	$scopetype="grupowe";
}
else if($_POST[st]=="undefined")
{
	//tutaj wiem, że będzie modyfikacja (w html-u funkcja na to tylko pozwala)
	$query2="SELECT * FROM events WHERE uid='".$_POST[i]."'";
	$result2=mysqli_query($con,$query2);
	$row2=mysqli_fetch_array($result2);
			if($row2[type]=="indywidualne")
			{
				echo "<p><b>Osoby objęte wydarzeniem:</b></p>
				<p>Filtruj: <input type='text' id='peopleFilterTB' onkeyup=\"filterPersons()\"></p><div class='addEventCoveredClassesList'>";
				$scopetype="indywidualne";
				$funkcjaButtonaGotowe="modifyEvent('indywidualne')";
			}
			else if($row2[type]=="klasowe")
			{
				echo "<p><b>Klasy objęte wydarzeniem:</b></p><p>";
				$scopetype="klasowe";
				$funkcjaButtonaGotowe="modifyEvent('klasowe')";
			}
			else if($row2[type]=="grupowe")
			{
				echo "<p><b>Grupy objęte wydarzeniem:</b></p><p>";
				$scopetype="grupowe";
				$funkcjaButtonaGotowe="modifyEvent('grupowe')";
			}
	
}
	
if($_POST[c]=="nauczyciel"||$_POST[c]=="dyrektor"||$_POST[c]=="administrator")
{
$query="SELECT * FROM users ORDER BY CHAR_LENGTH(user_class) ASC, user_class ASC, username ASC";
$result=mysqli_query($con,$query);
$klasy=array();
$osoby=array();
while($row=mysqli_fetch_array($result))
{

		if($scopetype=="indywidualne")
		{
		
			if(!in_array($row[id],$osoby))
			{
				if($_POST[t]=="modifyevent")
				{
					$query2="SELECT * FROM events WHERE uid='".$_POST[i]."'";
					$result2=mysqli_query($con,$query2);
					while($row2=mysqli_fetch_array($result2))
					{
						if($row2[type]=="indywidualne")
						{
							$osobyobjete=array();
							$query_09="SELECT scope FROM events_scopes WHERE event_uid='".$row2['uid']."'";
							$result_09=mysqli_query($con,$query_09);
							while($row_09=mysqli_fetch_array($result_09))
							{
								$osobyobjete[]=$row_09['scope'];
							}

							if(in_array($row[id],$osobyobjete))
							{ $checkedstate = "checked"; }
							else
							{ $checkedstate = ""; }
						}
					}
				}
			
			$osoby[]=$row[id];
			
				echo "<div class='scopesdiv' id='uczen".$row[id]."'><input type='checkbox' class='scopes' id='chkbox".$row[id]."' value='".$row[username]."' ".$checkedstate." /><label for='chkbox".$row[id]."'> ".$row[username]." - ".$row[user_class]."</label></div>";
			}
		}
		else if($scopetype=="klasowe")
		{
			if($row[user_class]!="nauczyciel"&&$row[user_class]!="dyrektor"&&$row[user_class]!="administrator")
			{
			if(!in_array($row[user_class],$klasy))
			{
				if($_POST[t]=="modifyevent")
				{
					$query2="SELECT * FROM events WHERE uid='".$_POST[i]."'";
					$result2=mysqli_query($con,$query2);
					while($row2=mysqli_fetch_array($result2))
					{
						if($row2[type]=="klasowe")
						{
							$klasyobjete=array();
							$query_09="SELECT scope FROM events_scopes WHERE event_uid='".$row2['uid']."'";
							$result_09=mysqli_query($con,$query_09);
							while($row_09=mysqli_fetch_array($result_09))
							{
								$klasyobjete[]=$row_09['scope'];
							}
							if(in_array($row[user_class],$klasyobjete))
							{ $checkedstate = "checked"; }
							else
							{ $checkedstate = ""; }
						}
					}
				}
			
			$klasy[]=$row[user_class];
			echo "<input type='checkbox' class='scopes' id='chkBox".$row[user_class]."' value='".$row[user_class]."' ".$checkedstate." /><label for='chkBox".$row[user_class]."'> ".$row[user_class]."</label><br>";
			
			}
			}
		}
		
	
}
if($scopetype=="grupowe")
		{
			$querygr="SELECT * FROM groups";
			$resultgr=mysqli_query($con,$querygr);
			while($rowgr=mysqli_fetch_array($resultgr))
			{
				if($_POST[t]=="modifyevent")
				{
					$query2="SELECT * FROM events WHERE uid='".$_POST[i]."'";
					$result2=mysqli_query($con,$query2);
					while($row2=mysqli_fetch_array($result2))
					{
						if($row2[type]=="grupowe")
						{
							$grupyobjete=array();
							$query_09="SELECT scope FROM events_scopes WHERE event_uid='".$row2['uid']."'";
							$result_09=mysqli_query($con,$query_09);
							while($row_09=mysqli_fetch_array($result_09))
							{
								$grupyobjete[]=$row_09['scope'];
							}


							if(in_array($rowgr[uid],$grupyobjete))
							{ $checkedstate = "checked"; }
							else
							{ $checkedstate = ""; }
						}
					}
				}
			
			echo "<input type='checkbox' class='scopes' value='".$rowgr[uid]."' id='chkbox".$rowgr[uid]."' ".$checkedstate." /><label for='chkbox".$rowgr[uid]."'> ".$rowgr[name]."</label><br>";
			}
			}
}
else
{
	if($scopetype=="klasowe")
	{
		echo "<big>".$_POST[c]."</big>";
	}
	else if($scopetype=="grupowe")
	{
		$querygr="SELECT * FROM groups";
		$resultgr=mysqli_query($con,$querygr);
		$grupyusera=array();
		while($rowgr=mysqli_fetch_array($resultgr))
		{
			$userzywgrupie=array();
			$query2="SELECT * FROM group_members WHERE groupuid='".$rowgr['uid']."'";
			$result2=mysqli_query($con,$query2);
			while($row2=mysqli_fetch_array($result2))
			{
				$userzywgrupie[]=$row2['memberid'];
			}

			if(in_array($_POST[uid],$userzywgrupie))
			{
				if($_POST[t]=="modifyevent")
				{
					$query2="SELECT * FROM events WHERE uid='".$_POST[i]."'";
					$result2=mysqli_query($con,$query2);
					while($row2=mysqli_fetch_array($result2))
					{
						if($row2[type]=="grupowe")
						{
							$grupyobjete=array();
							$query_09="SELECT scope FROM events_scopes WHERE event_uid='".$row2['uid']."'";
							$result_09=mysqli_query($con,$query_09);
							while($row_09=mysqli_fetch_array($result_09))
							{
								$grupyobjete[]=$row_09['scope'];
							}

							if(in_array($rowgr[uid],$grupyobjete))
							{ $checkedstate = "checked"; }
							else
							{ $checkedstate = ""; }
						}
					}
				}
			
			echo "<input type='checkbox' class='scopes' value='".$rowgr[uid]."' id='chkbox".$rowgr[uid]."' ".$checkedstate." /><label for='chkbox".$rowgr[uid]."'> ".$rowgr[name]."</label><br>";
			}
		}
	}
}

if($scopetype=="indywidualne")
{
	echo "</div>";
}
else if($scopetype=="klasowe")
{
	echo "</p>";
}


echo "</div>
<div class='addEventDate'>
<p><b>Data wydarzenia:</b></p>
<p class='addEventDateElement'><input type='text' id='datepicker' ";
if($dzien!="")
{
	echo "value='".$dzien.".".$miesiac.".".$rok."'";
}
else
{
	if($_POST['m']<10)
		$newmies="0".$_POST['m'];
	else
		$newmies=$_POST['m'];

	if($_POST['d']<10)
		$newdzien="0".$_POST['d'];
	else
		$newdzien=$_POST['d'];
	echo "value='".$newdzien.".".$newmies.".".$_POST['y']."'";
}
echo " /></p></div>";
/*echo"
<p class='addEventDateElement'>Dzień: <input style='width:50px;' type='text' id='dateday' maxlength='2' value='".$dzien."' /></p>
<p class='addEventDateElement'>Miesiąc: <select style='width:100px;' id='datemonth'>";
$miesiace=array("styczeń","luty","marzec","kwiecień","maj","czerwiec","lipiec","sierpień","wrzesień","październik","listopad","grudzień");

for($i=0;$i<count($miesiace);$i++)
{
	if(intval($miesiac)==$i+1)
	{ $selsel='selected'; }
	else
	{ $selsel=''; }
	
	echo "<option value='".($i+1)."' ".$selsel.">".$miesiace[$i]."</option>";
}

echo "</select>
</p>
<p class='addEventDateElement'>Rok: <input style='width:50px;' type='text' id='dateyear' maxlength='4' value='".$rok."' /></p>
</div>";*/
echo "</center>
<div class='interactionWindowButtons'><button class='confirmButton mediumButton centeredButton' onclick=\"".$funkcjaButtonaGotowe."\">Gotowe</button>
<button class='cancelButton mediumButton centeredButton' onclick='$(\"div.addcontent\").fadeOut();'>Anuluj</button></div>";
}
else if ($_POST[t]=="managegroups")
{
	echo "<p class='interactionWindowHeader'>Grupy w info<img onclick='hideInteractionWindow()' class='closingXInHeader' src='../images/closingx.png'></p>
	<div class='interactionWindowContent'>
	<p class='shortNotice'>kliknij na nazwę grupy, by zobaczyć więcej informacji</p>
	<div class='groupList dimmedBackground padded5'>";
	$query="SELECT * FROM groups ORDER BY name ASC";
	$result=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($result))
	{
		echo "<div class='groupItself'><span class='groupName' onclick='showAddContent(\"viewgroup\",\"".$row[uid]."\")'>".$row[name]."</span>";
		if($_POST[c]=="dyrektor"||$_POST[c]=="administrator"||$_POST[c]=="nauczyciel")
		{
			echo "<div class='groupAction'><span class='groupAction' style='margin-right:10px;' onclick='showAddContent(\"createOrEditGroup\",\"edit\",\"".$row[uid]."\");'><img src='../images/edit-black.png' width=10 height=10> edytuj</span><span onclick='deleteGroup(\"".$row[uid]."\",true);'><img src='../images/delete-black.png' width=10 height=10> usuń</span></div>";
		}
		echo "</div>";
	}
	
	echo "</div></div><div class='interactionWindowButtons'>";
	if($_POST[c]=="dyrektor"||$_POST[c]=="administrator"||$_POST[c]=="nauczyciel")
	{
		echo "<button class='confirmButton mediumButton centeredButton' onclick='showAddContent(\"createOrEditGroup\",\"create\");'>Dodaj grupę</button>";
	}
	echo "<button class='cancelButton mediumButton centeredButton' onclick='$(\"div.addcontent\").fadeOut();'>Zakończ</button></div>";
}
else if ($_POST[t]=="createOrEditGroup")
{
	if($_POST[st]=="create")
	{
		$funkcjaButtonaGotowe="createGroup(true);";
		echo "<p class='interactionWindowHeader'>Dodaj grupę<img onclick='hideInteractionWindow()' class='closingXInHeader' src='../images/closingx.png'></p>
		<div class='interactionWindowContent'>
		<div class='dimmedBackground padded5'><input type='text' id='nazwagrupy' placeholder='nazwa grupy'></div>
		<div class='dimmedBackground padded5'>
		<h4>Członkowie grupy<p id='filterFieldPara'>Filtruj: <input type='text' id='peopleFilterTB' onkeyup=\"filterPersons()\"></p></h4>
		<div class='addGroupCoveredPersons'>";
		$query="SELECT * FROM users ORDER BY CHAR_LENGTH(user_class) ASC, user_class ASC, username ASC";
		$result=mysqli_query($con,$query);
		while($row=mysqli_fetch_array($result))
		{
			echo "<div class='scopesdiv' id='uczen".$row[id]."'><input type='checkbox' class='scopes' id='chkbox".$row[id]."' value='".$row[username]."'/> <label for='chkbox".$row[id]."'>".$row[username]." - ".$row[user_class]."</label></div>";
		}
		echo "</div></div></div>";
	}
	else if($_POST[st]=="edit")
	{
		$funkcjaButtonaGotowe="editGroup();";
		
		$query="SELECT * FROM groups WHERE uid='".$_POST[gid]."'";
		$result=mysqli_query($con,$query);
		
		while($row=mysqli_fetch_array($result))
		{
		echo "<p class='interactionWindowHeader'>Modyfikuj grupę<img onclick='hideInteractionWindow()' class='closingXInHeader' src='../images/closingx.png'></p>
		<div class='interactionWindowContent'>
		<div class='dimmedBackground padded5'><input type='text' id='nazwagrupy' value='".$row[name]."' placeholder='nazwa grupy'></div>
		<div class='dimmedBackground padded5'>
		<h4>Członkowie grupy</h4>
		<div class='addGroupCoveredPersons'>
		<p style='margin-top:0px;'>Filtruj: <input type='text' id='peopleFilterTB' onkeyup=\"filterPersons()\"></p>";
		
		$members=array();
		$query2="SELECT * FROM group_members WHERE groupuid='".$_POST[gid]."'";
		$result2=mysqli_query($con,$query2);
		
		while($row2=mysqli_fetch_array($result2))
		{
			$members[]=$row2['memberid'];
		}

		$query2="SELECT * FROM users";
		$result2=mysqli_query($con,$query2);
		while($row2=mysqli_fetch_array($result2))
		{
			if(in_array($row2[id],$members))
			{	$checkedstate="checked";	}
			else
			{	$checkedstate="";	}
			echo "<div class='scopesdiv' id='uczen".$row2[id]."'><input type='checkbox' class='scopes' id='chkbox".$row2[id]."' value='".$row2[username]."' ".$checkedstate."/><label for='chkbox".$row2[id]."'> ".$row2[username]." - ".$row2[user_class]."</label></div>";
		}
		echo "</div></div></div>";
		}
	}
	
	
	echo "<div class='interactionWindowButtons'><button class='confirmButton mediumButton centeredButton' onclick=\"".$funkcjaButtonaGotowe."\">Gotowe</button>
	<button class='cancelButton mediumButton centeredButton' onclick='showAddContent(\"managegroups\");'>Anuluj</button></div>";
}
else if ($_POST[t]=="viewgroup")
{
	$query="SELECT * FROM groups WHERE uid='".$_POST[st]."'";
	$result=mysqli_query($con,$query);
	$czlonkowie=array();
	while($row=mysqli_fetch_array($result))
	{
		$czlonkowieid=array();
		$query2="SELECT * FROM group_members WHERE groupuid='".$_POST[st]."'";
		$result2=mysqli_query($con,$query2);
		
		while($row2=mysqli_fetch_array($result2))
		{
			$czlonkowieid[]=$row2['memberid'];
		}

		$query2="SELECT * FROM users";
		$result2=mysqli_query($con,$query2);
		while($row2=mysqli_fetch_array($result2))
		{
			if(in_array($row2[id],$czlonkowieid))
			{
				$czlonkowie[]=$row2[username]." - ".$row2[user_class];
			}
		}
		$nazwagrupy=$row[name];
	}
	echo "<p class='interactionWindowHeader'>Szczegóły grupy<img onclick='hideInteractionWindow()' class='closingXInHeader' src='../images/closingx.png'></p>
	<div class='dimmedBackground'><p style='font-size:16px; margin-top:5px; margin-bottom:5px;'><b>Nazwa: </b>".$nazwagrupy."</p></div><div class='groupList dimmedBackground'>
	<p style='font-size:16px; font-weight:bold; margin-top:5px; margin-bottom:5px;'>Członkowie grupy:</p>";
	for($i=0;$i<count($czlonkowie);$i++)
	{
		echo "<p style='margin-bottom:0px; margin-top:0px;'>".$czlonkowie[$i]."</p>";
	}
	echo "</div><div class='interactionWindowButtons'><button class='cancelButton mediumButton centeredButton' onclick='showAddContent(\"managegroups\");'>Powrót</button></div>";
}
else if ($_POST[t]=="createormodifyreplacement")
{
	if($_POST[st]=='')
	{
		echo "<p class='interactionWindowHeader'>Dodaj zastępstwo<img onclick='hideInteractionWindow()' class='closingXInHeader' src='images/closingx.png'></p>
		<div class='interactionWindowContent'>
		<p>Zastępowana: <input type='text' id='replacedText'></p>
		<p>Zastępująca: <input type='text' id='replacingText'><br /><input type='checkbox' id='noLesson'><label for='noLesson'>klasa/grupa zwolniona</label></p>
		<div class='inlinowe'><p class='noBottomMargin'><input type='radio' name='replacementScopeRadio' id='replacementForClass' checked><label for='replacementForClass'>Zastępstwo klasowe</label></p><p><select id='replacementScopeClass'>";
		
		$queryshowclasses="SELECT user_class FROM users ORDER BY CHAR_LENGTH(user_class) ASC, user_class ASC, username ASC";
		$result=mysqli_query($con,$queryshowclasses);
		$klasy = array();
		while($row=mysqli_fetch_array($result))
		{
			if(!in_array($row[user_class],$klasy))
			{
				echo "<option value='".$row[user_class]."'>".$row[user_class]."</option>";
				$klasy[]=$row[user_class];
			}
		}
		
		echo "</select></div>
		<div class='inlinowe' style='margin:auto 10px auto 10px'>LUB</div>
		<div class='inlinowe'><p class='noBottomMargin'><input type='radio' name='replacementScopeRadio' id='replacementForGroups'><label for='replacementForGroups'>Zastępstwo grupowe</label></p><p><select id='replacementScopeGroup'>";
		
		$queryshowgroups="SELECT * FROM groups ORDER BY name ASC";
		$result=mysqli_query($con,$queryshowgroups);
		$grupy = array();
		while($row=mysqli_fetch_array($result))
		{
			if(!in_array($row['uid'],$grupy))
			{
				echo "<option value='".$row['uid']."'>".$row[name]."</option>";
				$grupy[]=$row['uid'];
			}
		}
		
		echo "</select></div></p>
		<p>Nr lekcji: <input type='text' id='lessonNumber'></p>
		<p>Numer sali: <input type='text' id='roomNumber'></p>
		<p>Rodzaj: <input type='text' id='replacementSubject'></p>";
		if($_POST[m]<10){$miesiac='0'.$_POST[m];}
		else{$miesiac=$_POST[m];}
		echo "<p>Zastępstwo będzie dodane na dzień: ".$_POST[d].".".$miesiac.".".$_POST[y].".</p>
		</div>";
		
		$funkcjaButtonaGotowe="addReplacement('addition')";
	}
	else
	{
		$replaced="";
		$replacing="";
		$valclass="";
		$valgroup="";
		$querystart="SELECT * FROM replacements WHERE id='".$_POST[st]."'";
		$resultstart=mysqli_query($con,$querystart);
		while($rowstart=mysqli_fetch_array($resultstart))
		{
		echo "<p class='interactionWindowHeader'>Modyfikuj zastępstwo</p>
		<div class='interactionWindowContent'>";
		
		$queryshowuser="SELECT username FROM users WHERE id='".$rowstart[replaced]."'";
		$result=mysqli_query($con,$queryshowuser);
		while($row=mysqli_fetch_array($result))
		{
			$replaced = $row[username];
			echo "<p>Zastępowana: <input type='text' id='replacedText' value='".$row[username]."'></p>";
		}
		if($replaced=='')
		{
			echo "<p>Zastępująca: <input type='text' id='replacingText' value='".$rowstart[replaced]."'></p>";
		}
			
		$queryshowuser="SELECT username FROM users WHERE id='".$rowstart[replacing]."'";
		$result=mysqli_query($con,$queryshowuser);
		while($row=mysqli_fetch_array($result))
		{
			$replacing = $row[username];
			echo "<p>Zastępująca: <input type='text' id='replacingText' value='".$row[username]."'></p>";
		}
		if($replacing=='')
		{
			echo "<p>Zastępująca: <input type='text' id='replacingText' value='".$rowstart[replacing]."'></p>";
		}
		
		echo "<div class='inlinowe'><p class='noBottomMargin'><input type='radio' name='replacementScopeRadio' id='replacementForClass'";

		if($rowstart[scopetype]=='klasowe')
		{
			echo "checked";
		}

		echo " ><label for='replacementForClass'>Zastępstwo klasowe</label></p><p><select id='replacementScopeClass'>";
		
		$queryshowclasses="SELECT user_class FROM users ORDER BY CHAR_LENGTH(user_class) ASC, user_class ASC, username ASC";
		$result=mysqli_query($con,$queryshowclasses);
		$klasy = array();
		while($row=mysqli_fetch_array($result))
		{
			if(!in_array($row[user_class],$klasy))
			{
				if($row[user_class]==$rowstart[scope])
				{	$selsel = 'selected';	}
				else
				{	$selsel = '';	}

				echo "<option value='".$row[user_class]."' ".$selsel.">".$row[user_class]."</option>";
				$klasy[]=$row[user_class];
			}
		}
		
		echo "</select></div>
		<div class='inlinowe' style='margin:auto 10px auto 10px'>LUB</div>
		<div class='inlinowe'><p class='noBottomMargin'><input type='radio' name='replacementScopeRadio' id='replacementForGroups'";
		if($rowstart[scopetype]=='grupowe')
		{
			echo "checked";
		}
		echo " ><label for='replacementForGroups'>Zastępstwo grupowe</label></p><p><select id='replacementScopeGroup'>";
		
		$queryshowgroups="SELECT * FROM groups ORDER BY uid ASC";
		$result=mysqli_query($con,$queryshowgroups);
		$grupy = array();
		while($row=mysqli_fetch_array($result))
		{
			if(!in_array($row['uid'],$grupy))
			{
				if($row['uid']==$rowstart['scope'])
				{	$selsel = 'selected';	}
				else
				{	$selsel = '';	}

				echo "<option value='".$row['uid']."' ".$selsel.">".$row[name]."</option>";
				$grupy[]=$row['uid'];
			}
		}
		
		echo "</select></div></p>
		<p>Nr lekcji: <input type='text' id='lessonNumber' value='".$rowstart[lesson]."'></p>
		<p>Numer sali: <input type='text' id='roomNumber' value='".$rowstart[room]."'></p>
		<p>Rodzaj: <input type='text' id='replacementSubject' value='".$rowstart[subject]."'></p>";
		echo "<p>Zastępstwo usytuowane jest ciągle w dniu: ".$rowstart[date].".</p>
		</div>";
		}
		
		$funkcjaButtonaGotowe="modifyReplacement('".$_POST[st]."')";
	}
	echo "<div class='interactionWindowButtons'><button class='confirmButton mediumButton centeredButton' onclick=\"".$funkcjaButtonaGotowe."\">Gotowe</button>
	<button class='cancelButton mediumButton centeredButton' onclick='$(\"div.addcontent\").fadeOut();'>Anuluj</button></div>";
}
else if ($_POST[t]=="changepass")
{
	echo "<p class='interactionWindowHeader'>Zmień hasło</p>
	<div class='interactionWindowContent' id='changePass'>
	<div><input type='password' id='oldpass' class='settingsInput' placeholder='stare hasło'/></div>
	<div><input type='password' id='newpass' class='settingsInput' placeholder='nowe hasło'/></div>
	</div>
	<div class='interactionWindowButtons'><button class='confirmButton mediumButton centeredButton' onclick='zmienhaslo()'>Gotowe</button>
	<button class='cancelButton mediumButton centeredButton' onclick='$(\"div.addcontent\").fadeOut();'>Anuluj</button></div>";
}
else if ($_POST[t]=="changeprofilepicture")
{
	echo "<p class='interactionWindowHeader'>Zmień zdjęcie profilowe</p>
	<div class='interactionWindowContent'>
	<form action='changeprofilepicture.php' method='post' enctype='multipart/form-data'>
	<p><label for='file'>Wybierz zdjęcie (tylko *.jpg):</label>
	<input type='file' name='filename'></p>
	<p style='font-style:italic'>Po kliknięciu 'Prześlij!' plik zostanie przesłany, a strona odświeżona.</p>
	<p><input type='submit' value='Prześlij!' class='addEventConfirm'></p>
	</form>
	</div>
	<div class='interactionWindowButtons'><button class='cancelButton mediumButton centeredButton' onclick='$(\"div.addcontent\").fadeOut();'>Wróć</button></div>";
	
}
else if ($_POST[t]=="changemail")
{
	echo "<p class='interactionWindowHeader'>Zmień email</p>
	<div class='interactionWindowContent'>
	<p style='font-style:italic;'>Pamiętaj, że twój e-mail jest również twoim loginem do info!</p>
	<div><input class='settingsInput' type='text' id='newmail' placeholder='nowy e-mail'/></p>
	</div>
	<div class='interactionWindowButtons'><button class='confirmButton mediumButton centeredButton' onclick='zmienmaila()'>Gotowe</button><button class='cancelButton mediumButton centeredButton' onclick='$(\"div.addcontent\").fadeOut();'>Anuluj</button></div>";
}
else if ($_POST[t]=="advert")
{
		echo "<p class='interactionWindowHeader' style='margin-top:40px'>Nowości w Info.Salez</p>
		<div class='interactionWindowContent'>
		<p><b>1.</b> Opcje modyfikacji użytkownika, jak i zarządzanie grupami zostało przeniesione do menu wywoływanego przyciskiem w prawym górnym rogu.</p>
		<p><img src='images/dropdowntomenu.jpg' style='width:80%'></p>
		<p><b>2.</b> <big>info.messenger</big> również został przeniesiony do menu po prawej stronie.</p>
		<p><img src='images/infomesstomenu.png' style='width:80%'></p>
		<div class='inlinowe' style='width:40%'><p><b>3.</b> Powstała <big>mobilna wersja</big> Info.Salez. Sprawdź ją już dziś na swoim smartfonie <img class='emoticon' src='images/emoticons/4.png'></p></div>
		<div class='inlinowe' style='width:40%'><p><img src='images/mobilescreenshot.png' style='width:60%'></p></div>
		</div>
		<p><b>4.</b> Aktualnie wybrany dzień w kalendarzu został otoczony <big>żółtą obwódką,</big> a do listy wydarzeń dodano kartę <big>wszystkie,</big> która pokazuje wydarzenia wszystkich typów w jednym miejscu i została ustawiona jako domyślna po załadowaniu strony.</p>
		<p><div class='inlinowe' style='width:40%'><img src='images/selecteddayborder.jpg' style='width:90%'></div><div class='inlinowe' style='width:40%; margin-right:5px'><img src='images/alllist.jpg' style='width:90%'></div></p>
		<p><b>5.</b> Dodano specjalną sekcję <big>\"Zastępstwa\"</big>, w której będzie można znaleźć zastępstwa planowane na wybrany dzień, a także sprawdzić ich ilość w kalendarzu.</p>
		<p><div class='inlinowe' style='width:60%'><img src='images/replacements.jpg' style='width:90%'></div><div class='inlinowe' style='width:20%; margin-right:5px'><img src='images/replacementsincal.jpg' style='width:90%'></div></p>
		<p><big>Przypominamy</big>, że wydarzenia klasowe reprezentuje kolor <span style='color:rgba(107,161,215,1); font-weight:bold'>niebieski</span>, indywidualne - <span style='color:rgba(215,160,99,1); font-weight:bold'>pomarańczowy</span>, a grupowe - <span style='color:rgba(163,204,42,1); font-weight:bold'>zielony</span>.</p>
		<p><img src='images/calendarday.jpg' style='width:20%'></p>
		<p style='font-style:italic; font-size:1.5em'>Wszystko dla twojej wygody!</p>
		<div class='interactionWindowButtons'><button class='confirmButton mediumButton centeredButton' onclick='$(\"div.addcontent\").fadeOut();'>Rozumiem</button></div>";
	
}
?>
