<?php
	session_start();
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
	die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

	$queryUserInfo="SELECT * FROM users WHERE id=".$_POST['id'];
	$resultUserInfo=mysqli_query($con,$queryUserInfo);
	$rowUserInfo=mysqli_fetch_array($resultUserInfo);

	if($rowUserInfo['user_class']=='nauczyciel'||$rowUserInfo['user_class']=='dyrektor'||$rowUserInfo['user_class']=='administrator')
		$queryClassAdve="SELECT * FROM adve WHERE scopetype='klasowe' AND isOn='Y'";
	else
		$queryClassAdve="SELECT adve.* FROM adve INNER JOIN adve_scopes ON adve.uid=adve_scopes.adve_uid WHERE adve.scopetype='klasowe' AND adve.isOn='Y' AND (adve_scopes.scope='".$rowUserInfo['user_class']."' OR adve.author='".$_POST['id']."')";

	$resultClassAdve=mysqli_query($con,$queryClassAdve);
	while($rowClassAdve=mysqli_fetch_array($resultClassAdve))
	{
		$queryAuthorInfo="SELECT username FROM users WHERE id='".$rowClassAdve['author']."'";
		$resultAuthorInfo=mysqli_query($con,$queryAuthorInfo);
		$rowAuthorInfo=mysqli_fetch_array($resultAuthorInfo);
		echo "<div class='adveItself classAdve'>
		<div class='adveItselfHeader'>".$rowClassAdve['title']."</div>
		<div class='adveItselfText'>".$rowClassAdve['content']."</div>
		<div class='adveItselfScope'>klasy: ";
		$query_87="SELECT scope FROM adve_scopes WHERE adve_uid='".$rowClassAdve['uid']."'";
		$result_87=mysqli_query($con,$query_87);
		$output="";
		while($row_87=mysqli_fetch_array($result_87))
		{
			$output.=$row_87['scope'].", ";
		}
		echo substr($output,0,-2)."</div>
		<div class='adveItselfMeta'>".$rowAuthorInfo['username']." - ".$rowClassAdve['addedDate']." - ".$rowClassAdve['addedHour']."</div>
		</div>
		</div>";
	}


	if($rowUserInfo['user_class']=='nauczyciel'||$rowUserInfo['user_class']=='dyrektor'||$rowUserInfo['user_class']=='administrator')
	{
		$queryGroupAdve="SELECT * FROM adve WHERE scopetype='grupowe' AND isOn='Y'";
		$resultGroupAdve=mysqli_query($con,$queryGroupAdve);
		while($rowGroupAdve=mysqli_fetch_array($resultGroupAdve))
		{
			$queryAuthorInfo="SELECT username FROM users WHERE id='".$rowGroupAdve['author']."'";
			$resultAuthorInfo=mysqli_query($con,$queryAuthorInfo);
			$rowAuthorInfo=mysqli_fetch_array($resultAuthorInfo);
			echo "<div class='adveItself groupAdve'>
			<div class='adveItselfHeader'>".$rowGroupAdve['title']."</div>
			<div class='adveItselfText'>".$rowGroupAdve['content']."</div>
			<div class='adveItselfScope'>grupy: ";
		$query_87="SELECT groups.name FROM adve_scopes INNER JOIN groups ON adve_scopes.scope=groups.uid WHERE adve_scopes.adve_uid='".$rowGroupAdve['uid']."'";
		$result_87=mysqli_query($con,$query_87);
		$output="";
		while($row_87=mysqli_fetch_array($result_87))
		{
			$output.=$row_87['name'].", ";
		}
		echo substr($output,0,-2)."</div>
			<div class='adveItselfMeta'>".$rowAuthorInfo['username']." - ".$rowGroupAdve['addedDate']." - ".$rowGroupAdve['addedHour']."</div>
			</div>
			</div>";
		}
	}
	else
	{
		$querygr="SELECT groupuid FROM group_members WHERE memberid=".$_POST['id'];
			$resultgr=mysqli_query($con,$querygr);
			$takenAdve=array();
			while($rowgr=mysqli_fetch_array($resultgr))
			{
				$queryGroupAdve="SELECT adve.* FROM adve INNER JOIN adve_scopes ON adve.uid=adve_scopes.adve_uid WHERE adve.scopetype='grupowe' AND adve.isOn='Y' AND (adve_scopes.scope='".$rowgr['groupuid']."' OR adve.author='".$_POST['id']."')";
				
				$resultGroupAdve=mysqli_query($con,$queryGroupAdve);
				while($rowGroupAdve=mysqli_fetch_array($resultGroupAdve))
				{
					if(!in_array($rowGroupAdve['uid'], $takenAdve))
					{
						$queryAuthorInfo="SELECT username FROM users WHERE id='".$rowGroupAdve['author']."'";
						$resultAuthorInfo=mysqli_query($con,$queryAuthorInfo);
						$rowAuthorInfo=mysqli_fetch_array($resultAuthorInfo);
						echo "<div class='adveItself groupAdve'>
						<div class='adveItselfHeader'>".$rowGroupAdve['title']."</div>
						<div class='adveItselfText'>".$rowGroupAdve['content']."</div>
			<div class='adveItselfScope'>grupy: ";
		$query_87="SELECT groups.name FROM adve_scopes INNER JOIN groups ON adve_scopes.scope=groups.uid WHERE adve_scopes.adve_uid='".$rowGroupAdve['uid']."'";
		$result_87=mysqli_query($con,$query_87);
		$output="";
		while($row_87=mysqli_fetch_array($result_87))
		{
			$output.=$row_87['name'].", ";
		}
		echo substr($output,0,-2)."</div>
						<div class='adveItselfMeta'>".$rowAuthorInfo['username']." - ".$rowGroupAdve['addedDate']." - ".$rowGroupAdve['addedHour']."</div>
						</div>
						</div>";
						$takenAdve[]=$rowGroupAdve['uid'];
					}
				}
			}
		
	}
	
?>