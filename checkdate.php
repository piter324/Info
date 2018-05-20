<?php
if(strtotime($_POST[year]."-".$_POST[month]."-".$_POST[day]) < strtotime(date('Y-m-d')))
{
	echo "Nie możesz dodawać wydarzeń do przeszłości";
}
else
{
	echo "passed";
}
?>