<?php
	$config_array = parse_ini_file("../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");


$ildniwmies=date("t",strtotime($_POST[year]."-".$_POST[month]."-01"));

if($_POST[day]==""||$_POST[month]==""||$_POST[year]=="")
{
	echo "Wprowadź datę wydarzenia";
}
else if(intval($_POST[month])>12 || intval($_POST[month])<1 || intval($_POST[day])>$ildniwmies || intval($_POST[day])<1 || !ctype_digit($_POST[day]) || !ctype_digit($_POST[year]))
{
	echo "Upewnij się, że wprowadzona data jest poprawna";
}
else if($_POST[text]=="")
{
	echo "Wprowadź tytuł wydarzenia";
}
else if($_POST[scope]=="")
{
	echo "Zaznacz odbiorców wydarzenia";
}
else if(strtotime($_POST[year]."-".$_POST[month]."-".$_POST[day]) < strtotime(date('Y-m-d')))
{
	echo "Nie możesz dodawać wydarzeń do przeszłości";
}
else
{
if ($_POST[month]<10&&substr($_POST[month],0,1)!="0")
{ $month="0".$_POST[month]; }
else
{ $month=$_POST[month]; }
if ($_POST[day]>10)
{ $day=$_POST[day]; }
else if (substr($_POST[day],0,1)!="0")
{ $day=$_POST[day]; }
else
{ $day=substr($_POST[day],1); }

$dateadd=date('Y-m-d');
$houradd=date('H:i:s');
$uid=uniqid();
$query="INSERT INTO events (author_id,text,details,subject,type,uid,day,month,year,added_date,added_hour) VALUES ('".$_POST[author_id]."','".$_POST[text]."','".$_POST[details]."','".$_POST[subject]."','".$_POST[type]."','".$uid."','".$day."','".$month."','".$_POST[year]."','".$dateadd."','".$houradd."')";
mysqli_query($con,$query);
$scopes = explode(",", $_POST['scope']);
foreach ($scopes as $scope) {
	$queryscope="INSERT INTO events_scopes (event_uid,scope) VALUES ('".$uid."','".$scope."')";
	mysqli_query($con,$queryscope);
}
$queryupdatetime="UPDATE table_updates SET dzien='".date("d")."', miesiac='".date("m")."', rok='".date("Y")."', godzina='".date("H")."', minuta='".date("i")."', sekunda='".date("s")."' WHERE nazwa_tabeli='events'";
mysqli_query($con,$queryupdatetime);
//echo $_POST[type];
echo "done";
}

?>
