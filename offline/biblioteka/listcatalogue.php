<?php
$con=mysqli_connect("localhost","infosalez_auto","infosalez2413","biblioteka");
$con->set_charset("utf8");

echo "<center><table><tr><td class='narrow tableHeader'>Numer katalogowy</td><td class='tableHeader'>Autor</td><td class='tableHeader'>Tytuł</td><td class='tableHeader'>Wydawnictwo</td><td class='tableHeader'>ISBN</td><td class='narrow tableHeader'>Dostępny?</td></tr>";

$query="SELECT id,autor,tytul,wydawnictwo,ISBN,dostepny FROM inwentarz WHERE ".$_POST[fk]." LIKE '%".$_POST[f]."%' ORDER BY autor ASC LIMIT 20";
$result=mysqli_query($con,$query);
while($row=mysqli_fetch_array($result))
{
	echo "<tr><td class='narrow'>".$row[id]."</td><td>".$row[autor]."</td><td>".$row[tytul]."</td><td>".$row[wydawnictwo]."</td><td><a href='https://www.google.pl/search?q=%27".$row[ISBN]."%27' target='_blank'>".$row[ISBN]."</a></td><td class='narrow'>".$row[dostepny]."</td></tr>";
}
echo "</table><center>";

?>