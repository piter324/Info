<?php
$data = date('Y-m-d');
$d = new DateTime($data);
$d->modify('last day of this month');
echo $d->format('d');
echo '<br>';
echo $data;
echo '<br>';
echo date('w',strtotime(date('Y-m').'-01'));
?>