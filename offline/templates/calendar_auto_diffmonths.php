<html>
<meta charset="UTF-8">
<style>
body
{
	font-family:Trebuchet MS;
	background:url('../images/back.png');
}
td.nazwydni
{
	background-color:rgba(203,181,251,0.8);
	height:50px;
	text-align:center;
	vertical-align:middle;
	font-size:20px;
}
td
{
	text-align:right;
	vertical-align:top;
	width:101px;
	height:80px;
}
td.roboczy
{
	background-color:rgba(255,255,255,0.8);
}
td.sobota
{
	background-color:rgba(200,200,200,0.8);
}
td.niedziela
{
	background-color:rgba(255,65,65,0.8);
}
td.nieobecny
{
	background:none;
}
p.nrdnia
{
	font-size:18px;
}
p.event
{
	text-align:center;
	margin: 20px 5px 5px 5px;
	font-size:14px;
	background-color:rgba(147,201,255,0.8);
	border-radius:2px;
}
p.dzisiaj
{
	text-align:center;
	margin: -15px 10px 0px 10px;
	font-size:14px;
	font-weight:bold;
	background-color:rgba(190,255,128,0.8);
	border-radius:2px;
}
</style>
<script src='../jquery.js'></script>
<script>
function przejdz(month,year)
{
window.location='calendar_auto_diffmonths.php?m='+month+'&y='+year;
}
</script>
<body>
<p>Dynamiczny kalendarz</p>
<p>Miesiąc: <input type='text' id='month'>&emsp;Rok: <input type='text' id='year'><button onclick="javascript:przejdz($('#month').val(), $('#year').val());">Przejdź</button>
<?php
echo "<table>
<tr>
<td class='nazwydni'>PON</td>
<td class='nazwydni'>WT</td>
<td class='nazwydni'>ŚR</td>
<td class='nazwydni'>CZW</td>
<td class='nazwydni'>PT</td>
<td class='nazwydni'>SOB</td>
<td class='nazwydni'>ND</td>
</tr>";
echo $_GET[y]."-".$_GET[m]."-01";
$d = new DateTime($_GET[y]."-".$_GET[m]."-01");
$d->modify('last day of this month');
$nrpierwdnia = date('w',strtotime($_GET[y]."-".$_GET[m].'-01'));
if($nrpierwdnia==0)
{
	$nrpierwdnia=7;
}
echo "<tr>";
for($j=1;$j<$nrpierwdnia;$j++)
{
	echo "<td class='nieobecny'></td>";
}
$nrdnia=$nrpierwdnia;
for($i=1; $i<=($d->format('d')); $i++)
{
	
	if($nrdnia>=8)
	{
		echo "</tr><tr>";
		$nrdnia=1;
	}
	
	if($nrdnia==6)
	{
		echo "<td class='sobota' id='".$nrdnia."' onclick=\"javascript:alert('sobota');\"><p class='".$nrdnia."' >".$i."</p>";
	}
	else if($nrdnia==7)
	{
		echo "<td class='niedziela' id='".$nrdnia."' onclick=\"javascript:alert('niedziela');\"><p class='".$nrdnia."' >".$i."</p>";
	}
	else
	{
		echo "<td class='roboczy' id='".$nrdnia."' onclick=\"javascript:alert('dzień roboczy');\"><p class='".$nrdnia."' >".$i."</p>";
	}
	if($_GET[y]==date('Y'))
	{
		if($_GET[m]==date('m'))
		{
			if($i==date('d'))
			{
				echo "<p class='dzisiaj'>DZISIAJ</p>";
			}
		}
	}
	echo "</td>";
	$nrdnia++;
	
}
for($j=$nrdnia;$j<=7;$j++)
{
	echo "<td class='nieobecny'></td>";
}
echo "</table>";
?>

<table style="margin-top:200px;">
<tr>
<td class='roboczy'></td>
<td class='roboczy'></td>
<td class='roboczy'></td>
<td class='roboczy'></td>
<td class='roboczy'></td>
<td class='sobota'><p class='nrdnia' onclick="javascript:alert('działa');">1</p></td>
<td class='niedziela' onclick="javascript:alert('działa');"><p class='nrdnia'>2</p><p class='event'><big>1</big> wydarzenie</p></td>
</tr>
</table>

</body>
</html>