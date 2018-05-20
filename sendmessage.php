<?php
	session_start();
	$con=mysqli_connect("localhost","infosalez_auto","infosalez2413",$_SESSION['db']);
	$con->set_charset("utf8");

$niedozwolone=array('<?php','javascript:','<script>','<input');
if($_POST[text]!="")
{
	/*$wysylac=false;
	for($i=0;$i<count($niedozwolone);$i++)
	{
		if(strpos($_POST[text],$niedozwolone[$i]) === true)
		{
			$wysylac=false;
		}
		else
		{
			$wysylac=true;
		}
	}*/
	$wysylac=true;
	if($wysylac==true)
	{
		$data = date('d-m-Y');
		$godzina = date('H:i:s');
		$query="INSERT INTO messages (sender,recipient,text,type,continuationof,date_when_sent,hour_when_sent) VALUES ('".$_POST[sid]."','".$_POST[rid]."','".$_POST[text]."','individual','','".$data."','".$godzina."')";
		mysqli_query($con,$query);
		echo "done";
	}
	else
	{
		echo "Twoja wiadomość zawiera niedozwoloną treść i nie została wysłana";
	}
}
?>
