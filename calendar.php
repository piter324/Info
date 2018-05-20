<?php
	$config_array = parse_ini_file("../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");

	if ($_POST[m]<10)
{
	$m="0".$_POST[m];
}
else
{
	$m=$_POST[m];
}
	
	//$result=mysqli_query($con,$query); - to jest niżej
	
	$miesiace=array("styczeń","luty","marzec","kwiecień","maj","czerwiec","lipiec","sierpień","wrzesień","październik","listopad","grudzień");
	$dnitygodnia=array("poniedziałek","wtorek","środa","czwartek","piątek","sobota","niedziela");
	echo "<div class='monthandyear'>".$miesiace[$_POST[m]-1]." ".$_POST[y]."</div><div class='calendarcontrols'><button class='calControlButton' onclick=calendarControl('rok',-1)><<</button><button class='calControlButton' onclick=calendarControl('miesiac',-1)><</button><button class='calControlButton' onclick=calendarControl('miesiac',1)>></button><button class='calControlButton' onclick=calendarControl('rok',1)>>></button></div>";
	echo "<table id='calendar'>
    <tr>";
    foreach ($dnitygodnia as $dzientygodnia) {
    	echo "<td class='nazwydni'>".$dzientygodnia."</td>";
    }
    echo "</tr>";
	$ilwydarzen=0;
    //echo $_POST[y]."-".$_POST[m]."-01";
    $d = new DateTime($_POST[y]."-".$_POST[m]."-01");
    $d->modify('last day of this month');
	//który dzień tygodnia jest pierwszy(0 dla niedzieli jest zamieniane na 7 poniżej)
    $nrpierwdnia = date('w',strtotime($_POST[y]."-".$_POST[m].'-01'));
    if($nrpierwdnia==0)
    {
        $nrpierwdnia=7;
    }
    echo "<tr>";
    for($j=1;$j<$nrpierwdnia;$j++)
    {
        echo "<td class='dzien nieobecny'></td>";
    }
    $nrdnia=$nrpierwdnia;
    for($i=1; $i<=($d->format('d')); $i++)
    {
        
        if($nrdnia>=8)
        {
            echo "</tr><tr>";
            $nrdnia=1;
        }
        if($_POST[y]==date('Y'))
        {
            if($_POST[m]==date('m'))
            {
                if($i==date('d'))
                {
                    $klasadzisiejsza="todayDay";
					$styldzisiejszyilwyd = "style='color:rgba(0,0,0,1);'";
                }
				else
				{
					$klasadzisiejsza="";
					$styldzisiejszyilwyd="";
				}
				if($i==$_POST[d])
				{
					$klasazaznaczonego="selectedDay";
				}
				else
				{
					$klasazaznaczonego="";
				}
            }
        }
        if($nrdnia==6)
        {
            echo "<td class='dzien sobota ".$klasadzisiejsza." ".$klasazaznaczonego."' id='".$i."' onclick=\"showDayDetails(".$i.",".$_POST[m].",".$_POST[y].",false);\"><p class='nrdnia' >".$i."</p>";
        }
        else if($nrdnia==7)
        {
            echo "<td class='dzien niedziela ".$klasadzisiejsza." ".$klasazaznaczonego."' id='".$i."' onclick=\"showDayDetails(".$i.",".$_POST[m].",".$_POST[y].",false);\"><p class='nrdnia' >".$i."</p>";
        }
        else
        {
            echo "<td class='dzien roboczy ".$klasadzisiejsza." ".$klasazaznaczonego."' id='".$i."' onclick=\"showDayDetails(".$i.",".$_POST[m].",".$_POST[y].",false);\"><p class='nrdnia' >".$i."</p>";
        }
        
		#region liczenie i umiejscawianie wydarzeń
		
		if ($_POST[c]=="nauczyciel" || $_POST[c]=="dyrektor" ||$_POST[c]=="administrator")
		{
			//ile wydarzen klasowych
			$query="SELECT COUNT(id) FROM events WHERE month=\"".$m."\" AND year=\"".$_POST[y]."\" AND day=".$i." AND type='klasowe'";
			$result=mysqli_query($con,$query);
			$row=mysqli_fetch_array($result);
			$ilwydarzenklas=$row['COUNT(id)'];
			
			//ile wydarzen indywidualnych, których jest autorem
			$query="SELECT COUNT(id) FROM events WHERE month=\"".$m."\" AND year=\"".$_POST[y]."\" AND day='".$i."' AND type='indywidualne' AND author_id='".$_POST['uid']."'";
			$result=mysqli_query($con,$query);
			$row=mysqli_fetch_array($result);
			$ilwydarzenindyw=$row['COUNT(id)'];
			//ile wydarzeń indywidualnych, w których bierze udział
			$query="SELECT COUNT(events.id) FROM events INNER JOIN events_scopes ON events_scopes.event_uid=events.uid WHERE month=\"".$m."\" AND year=\"".$_POST[y]."\" AND day='".$i."' AND type='indywidualne' AND events_scopes.scope=".$_POST['uid'];
			$result=mysqli_query($con,$query);
			$row=mysqli_fetch_array($result);
			$ilwydarzenindyw+=$row['COUNT(events.id)'];


			//ile wydarzen grupowych
			$query="SELECT COUNT(id) FROM events WHERE month=\"".$m."\" AND year=\"".$_POST[y]."\" AND day='".$i."' AND type='grupowe'";
			$result=mysqli_query($con,$query);
			$row=mysqli_fetch_array($result);
			$ilwydarzengrup = $row['COUNT(id)'];
			
			//ile zastepstw
			$query="SELECT * FROM replacements WHERE date='".$_POST[y].".".$m.".".$i."'";
			$result=mysqli_query($con,$query);
			while($row=mysqli_fetch_array($result))
			{
				$ilzastepstw++;
			}
		}
		else
		{
			//ile wydarzen klasowych, w ktorych klasa uzytkownika bierze udzial
			$query="SELECT COUNT(events.id) FROM events INNER JOIN events_scopes ON events_scopes.event_uid=events.uid WHERE month=\"".$m."\" AND year=\"".$_POST[y]."\" AND day='".$i."' AND type='klasowe' AND events_scopes.scope='".$_POST['c']."'";
			$result=mysqli_query($con,$query);
			$row=mysqli_fetch_array($result);
			$ilwydarzenklas=$row['COUNT(events.id)'];
			
			//ile wydarzen indywidualnych, w ktorych uzytkownik bierze udzial
			$query="SELECT COUNT(events.id) FROM events INNER JOIN events_scopes ON events_scopes.event_uid=events.uid WHERE month=\"".$m."\" AND year=\"".$_POST[y]."\" AND day='".$i."' AND type='indywidualne' AND events_scopes.scope=".$_POST['uid'];
			$result=mysqli_query($con,$query);
			$row=mysqli_fetch_array($result);
			$ilwydarzenindyw=$row['COUNT(events.id)'];
			
			//ile wydarzen grupowych, w ktorych grupa uzytkownika bierze udzial
			$querygr="SELECT groupuid FROM group_members WHERE memberid=".$_POST['uid'];
			$resultgr=mysqli_query($con,$querygr);
			$counted_events=array();
			while($rowgr=mysqli_fetch_array($resultgr))
			{
				$query_07="SELECT events.id FROM events INNER JOIN events_scopes ON events_scopes.event_uid=events.uid WHERE month=\"".$m."\" AND year=\"".$_POST[y]."\" AND day='".$i."' AND type='grupowe' AND events_scopes.scope='".$rowgr['groupuid']."'";
				$result_07=mysqli_query($con,$query_07);
				while($row_07=mysqli_fetch_array($result_07))
				{
					if(!in_array($row_07['events.id'], $counted_events))
					{
						$ilwydarzengrup++;
						$counted_events[]=$row_07['events.id'];
					}
				}
				
			}
			
			//ile zastepstw
			//dla klas usera
			$query="SELECT * FROM replacements WHERE date='".$_POST[y]."-".$m."-".$i."' AND scopetype='klasowe'";
			$result=mysqli_query($con,$query);
			while($row=mysqli_fetch_array($result))
			{
				if($row[scope]==$_POST[c])
				{
					$ilzastepstw++;
				}
			}		
			
			//dla grup usera
			$query="SELECT * FROM replacements WHERE date='".$_POST[y]."-".$m."-".$i."' AND scopetype='grupowe'";
			$result=mysqli_query($con,$query);
			while($row=mysqli_fetch_array($result))
			{
				$querygr="SELECT * FROM groups WHERE uid='".$row[scope]."'";
				$resultgr=mysqli_query($con,$querygr);
				while($rowgr=mysqli_fetch_array($resultgr))
				{
					$userzywgrupie=array();
					$query_07="SELECT * FROM group_members WHERE groupuid='".$rowgr[uid]."'";
					$result_07=mysqli_query($con,$query_07);
					while($row_07=mysqli_fetch_array($result_07))
					{
						$userzywgrupie[]=$row_07['memberid'];
					}

					if(in_array($_POST[uid],$userzywgrupie))
					{
						$ilzastepstw++;
					}
					
				}
	
			}
	}
		//wyświetlanie wydarzeń klasowych i indywidualnych
		if($ilwydarzenklas!=0)
			{
				$koncowa=substr($ilwydarzenklas, -1);
			
				if($ilwydarzenklas==1) $wydwyd="wydarzenie";
				else if ($koncowa>=2&&$koncowa<=4&&$ilwydarzenklas<10) $wydwyd="wydarzenia";
				else $wydwyd="wydarzeń";
			
				echo "<p class='classevent' ".$styldzisiejszyilwyd."><big>".$ilwydarzenklas."</big><span class='wydwyd'> ".$wydwyd."</span></p>";
			}
		if($ilwydarzenindyw!=0)
			{
				$koncowa=substr($ilwydarzenindyw, -1);
			
				if($ilwydarzenindyw==1) $wydwyd="wydarzenie";
				else if ($koncowa>=2&&$koncowa<=4) $wydwyd="wydarzenia";
				else $wydwyd="wydarzeń";
			
				echo "<p class='individualevent' ".$styldzisiejszyilwyd."><big>".$ilwydarzenindyw."</big><span class='wydwyd'> ".$wydwyd."<span></p>";
			}
		if($ilwydarzengrup!=0)
			{
				$koncowa=substr($ilwydarzengrup, -1);
			
				if($ilwydarzengrup==1) $wydwyd="wydarzenie";
				else if ($koncowa>=2&&$koncowa<=4) $wydwyd="wydarzenia";
				else $wydwyd="wydarzeń";
			
				echo "<p class='groupevent' ".$styldzisiejszyilwyd."><big>".$ilwydarzengrup."</big><span class='wydwyd'> ".$wydwyd."</span></p>";
			}
		if($ilzastepstw!=0)
		{
			$koncowa=substr($ilzastepstw, -1);
			
			if($ilzastepstw==1) $wydwyd="zastępstwo";
			else if ($koncowa>=2&&$koncowa<=4) $wydwyd="zastępstwa";
			else $wydwyd="zastępstw";
			
			echo "<p class='replacementsInCal' ".$styldzisiejszyilwyd."><big>".$ilzastepstw."</big><span class='wydwyd'> ".$wydwyd."</span></p>";
		}
		#endregion
        echo "</td>";
        $nrdnia++;
        $ilwydarzenklas=0;
		$ilwydarzenindyw=0;
		$ilwydarzengrup=0;
		$ilzastepstw=0;
    }
    for($j=$nrdnia;$j<=7;$j++)
    {
        echo "<td class='nieobecny'></td>";
    }
    echo "</table>";
    ?>
