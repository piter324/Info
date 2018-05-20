<?php
$con=mysqli_connect("localhost","infosalez_auto","infosalez2413");
$con->set_charset("utf8");
$informacje = file($_GET[filename]);

$znakizr=array("ą","ć","ę","ł","ń","ó","ś","ź","ż","Ż");
$znakidoc=array("†","Ť","‘","’","¤","˘","ž","¦","§","ˇ");
echo"<style>
td
{
	border:1px solid #000000;
}
table
{
	border-spacing:0px;
}
</style>";
echo "<meta charset='UTF-8'><table><tr><td>id</td><td>sygnatura</td><td>autor</td><td>tytuł</td><td>wydawnictwo</td><td>miejsce_wydania</td><td>rok_wydania</td><td>wydanie</td><td>seria</td><td>ISBN</td><td>slowo_kluczowe</td><td>UKD</td><td>objetosc</td><td>ilustracje</td><td>format</td><td>dokumenttow</td><td>uwagi</td></tr>";
for($i=0;$i<count($informacje);$i++)
{
	$informacje[$i]=str_replace($znakidoc,$znakizr,$informacje[$i]);
	$numerinformacji=substr($informacje[$i],0,3);
	$info=substr($informacje[$i],5);
	
	if($numerinformacji=="200")
	{
		$tytul=substr($info,2);
		$tytul=str_replace("%a"," ",$tytul);
		//echo $tytul;
	}
	else if($numerinformacji=="201")
	{
		$autor=substr($info,2);
		//echo $autor;
	}
	else if($numerinformacji=="205")
	{
		$wydanie=substr($info,2);
		//echo $wydanie;
	}
	else if($numerinformacji=="225")
	{
		$seria=substr($info,2);
		//echo $seria;
	}
	else if($numerinformacji=="230")
	{
		$isbn=substr($info,2);
		//echo $isbn;
	}
	else if($numerinformacji=="300")
	{
		$uwagi=substr($info,2);
		//echo $uwagi;
	}
	else if($numerinformacji=="600")
	{
		$slowoklucz=substr($info,2);
		//echo $slowoklucz;
	}
	else if($numerinformacji=="680")
	{
		$ukd=substr($info,2);
		//echo $ukd;
	}
	else if($numerinformacji=="210")
	{
		$sklad=explode("%",$info);
		for($j=0;$j<count($sklad);$j++)
		{
			//echo $sklad[$j]." ";
			if(substr($sklad[$j],0,1)=="a")
			{
				$mwydania=substr(substr($sklad[$j],1),0,-1);
				//echo $mwydania."<br>";
			}
			else if(substr($sklad[$j],0,1)=="c")
			{
				$wydawnictwo=substr(substr($sklad[$j],1),0,-1);
				//echo $wydawnictwo."<br>";
			}
			else if(substr($sklad[$j],0,1)=="d")
			{
				$rokwyd=substr(substr($sklad[$j],1),0,-1);
				//echo $rokwyd."<br>";
			}
		}
	}
	else if($numerinformacji=="215")
	{
		$sklad=explode("%",$info);
		for($j=0;$j<count($sklad);$j++)
		{
			//echo $sklad[$j]." ";
			if(substr($sklad[$j],0,1)=="a")
			{
				$objetosc=substr(substr($sklad[$j],1),0,-1);
				//echo $objetosc."<br>";
			}
			else if(substr($sklad[$j],0,1)=="b")
			{
				$ilustracje=substr(substr($sklad[$j],1),0,-1);
				//echo $ilustracje."<br>";
			}
			else if(substr($sklad[$j],0,1)=="c")
			{
				$format=substr(substr($sklad[$j],1),0,-1);
				//echo $format."<br>";
			}
			else if(substr($sklad[$j],0,1)=="d")
			{
				$dokumenttow=substr(substr($sklad[$j],1),0,-1);
				//echo $dokumenttow."<br>";
			}
		}
	}
	else if($numerinformacji=="820")
	{
		$sklad=explode("%",$info);
		for($j=0;$j<count($sklad);$j++)
		{
			//echo $sklad[$j]." ";
			if(substr($sklad[$j],0,1)=="a")
			{
				$nrinw=substr($sklad[$j],1);
				//echo $nrinw."<br>";
			}
			else if(substr($sklad[$j],0,1)=="b")
			{
				$sygnatura=substr($sklad[$j],1);
				//echo $sygnatura."<br>";
			}
		}
		echo "<tr><td>$nrinw</td><td>$sygnatura</td><td>$autor</td><td>$tytul</td><td>$wydawnictwo</td><td>$mwydania</td><td>$rokwyd</td><td>$wydanie</td><td>$seria</td><td>$isbn</td><td>$slowoklucz</td><td>$ukd</td><td>$objetosc</td><td>$ilustracje</td><td>$format</td><td>$dokumenttow</td><td>$uwagi</td></tr>";
		$nrinw="";
		$sygnatura="";
		$autor="";
		$tytul="";
		$wydawnictwo="";
		$mwydania="";
		$rokwyd="";
		$wydanie="";
		$seria="";
		$isbn="";
		$slowoklucz="";
		$ukd="";
		$objetosc="";
		$ilustracje="";
		$format="";
		$dokumenttow="";
		$uwagi="";
		
	}
	//echo "<br>";
}
echo "</table>";
?>