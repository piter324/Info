<?php
$imieninki=file("../imieniny.txt");
for($i=0;$i<count($imieninki);$i++)
{
	$dzien=substr($imieninki[$i],0,2);
	$miesiac=substr($imieninki[$i],2,2);
	$imiona=substr($imieninki[$i],4);
	if(date('d')==$dzien&&date('m')==$miesiac)
	{
		$imionaexplode=explode(",",$imiona);
		echo "Dziś są imieniny: ";
		for($j=0;$j<count($imionaexplode);$j++)
		{
			$imionadowyswietlenia.=$imionaexplode[$j].", ";
		}
		$imionadowyswietlenia=substr($imionadowyswietlenia,0,-4);
		echo $imionadowyswietlenia;
	}
}
?>