<?php
	session_start();
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

	if($_POST['number']==1)
	{
			$toInput='plus';
			$queryValidate="SELECT COUNT(id) FROM marks WHERE user_id='".$_SESSION['userid']."' AND entry_uid='".$_POST['uid']."' AND type='minus'";
			$result=mysqli_query($con,$queryValidate);
			$row=mysqli_fetch_array($result);
			if($row['COUNT(id)']==1)
			{
				$query="DELETE FROM marks WHERE user_id='".$_SESSION['userid']."' AND entry_uid='".$_POST['uid']."' LIMIT 1";
				mysqli_query($con,$query);
				$query="INSERT INTO marks (entry_uid,user_id,type,entry_parent_uid) VALUES ('".$_POST['uid']."','".$_SESSION['userid']."','".$toInput."','".$_POST['parentid']."')";
				mysqli_query($con,$query);
				echo "done";
			}

			$queryValidate="SELECT COUNT(id) FROM marks WHERE user_id='".$_SESSION['userid']."' AND entry_uid='".$_POST['uid']."' AND type='plus'";
			$result=mysqli_query($con,$queryValidate);
			$row=mysqli_fetch_array($result);
			if($row['COUNT(id)']==0)
			{
				$query="INSERT INTO marks (entry_uid,user_id,type,entry_parent_uid) VALUES ('".$_POST['uid']."','".$_SESSION['userid']."','".$toInput."','".$_POST['parentid']."')";
				mysqli_query($con,$query);
				echo "done";
			}
	}
	else
	{		
			$toInput='minus';
			$queryValidate="SELECT COUNT(id) FROM marks WHERE user_id='".$_SESSION['userid']."' AND entry_uid='".$_POST['uid']."' AND type='plus'";
			$result=mysqli_query($con,$queryValidate);
			$row=mysqli_fetch_array($result);
			if($row['COUNT(id)']==1)
			{
				$query="DELETE FROM marks WHERE user_id='".$_SESSION['userid']."' AND entry_uid='".$_POST['uid']."' LIMIT 1";
				mysqli_query($con,$query);
				$query="INSERT INTO marks (entry_uid,user_id,type,entry_parent_uid) VALUES ('".$_POST['uid']."','".$_SESSION['userid']."','".$toInput."','".$_POST['parentid']."')";
				mysqli_query($con,$query);
				echo "done";
			}

			
			$queryValidate="SELECT COUNT(id) FROM marks WHERE user_id='".$_SESSION['userid']."' AND entry_uid='".$_POST['uid']."' AND type='minus'";
			$result=mysqli_query($con,$queryValidate);
			$row=mysqli_fetch_array($result);
			if($row['COUNT(id)']==0)
			{
				$query="INSERT INTO marks (entry_uid,user_id,type,entry_parent_uid) VALUES ('".$_POST['uid']."','".$_SESSION['userid']."','".$toInput."','".$_POST['parentid']."')";
				mysqli_query($con,$query);
				echo "done";
			}

			
	}
?>