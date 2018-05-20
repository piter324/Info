<?php
$con=mysqli_connect("localhost","infosalez_auto","infosalez2413");
$con->set_charset("utf8");

$query="SELECT * FROM biblioteka.wypozyczenia WHERE id_osoby='".$_POST[uid]."' AND data_zwrotu!=''";
$result=mysqli_query($con,$query);

while($row=mysqli_fetch_array($result))
{
	$queryinw="SELECT * FROM biblioteka.inwentarz WHERE id='".$row[id_pozycji]."'";
	$resultinw=mysqli_query($con,$queryinw);

	while($rowinw=mysqli_fetch_array($resultinw))
	{
		echo "<p><span style='font-weight:bold'>".$rowinw[rodzaj_materialu]." \"".$rowinw[tytul]."\" ".$rowinw[autor]."</span><br />wydawnictwo ".$rowinw[wydawnictwo]."; ISBN: <a href='https://www.google.pl/search?q=%27".$rowinw[ISBN]."%27' target='_blank'>".$rowinw[ISBN]."</a><br /><span style='font-size:12px'>wypożyczone: ".$row[data_wypozyczenia]."; oddane: ".$row[data_zwrotu]."</span></p>";
	}
}
?>