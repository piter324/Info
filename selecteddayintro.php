<?php
if ($_POST[m]<10)
{ $m="0".$_POST[m]; }
else
{ $m=$_POST[m]; }

//dbanie o poprawną nazwę dnia tygodnia
$nrdniatygodnia = date('w',strtotime($_POST[y].'-'.$m.'-'.$_POST[d]));
$dnitygodnia=array("niedziela","poniedziałek","wtorek","środa","czwartek","piątek","sobota");

echo "<p class='eventdate'>".$_POST[d].".".$m.".".$_POST[y]."</p>
<p class='eventdayoftheweek'>".$dnitygodnia[$nrdniatygodnia]."</p>";

?>