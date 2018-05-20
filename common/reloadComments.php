<?php
	session_start();
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
		die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

	$emotikonytext=array(":D",":d",":)","-_-",";)",":3",":(",":*","T-T","t-t",":o",":0",":<","<3",":P",":p",":x",":X");
			$emotikonyimages=array("<img class='emoticon' src='../images/emoticons/1.png'>",
			"<img class='emoticon' src='../images/emoticons/1.png'>",
			"<img class='emoticon' src='../images/emoticons/2.png'>",
			"<img class='emoticon' src='../images/emoticons/3.png'>",
			"<img class='emoticon' src='../images/emoticons/4.png'>",
			"<img class='emoticon' src='../images/emoticons/5.png'>",
			"<img class='emoticon' src='../images/emoticons/6.png'>",
			"<img class='emoticon' src='../images/emoticons/7.png'>",
			"<img class='emoticon' src='../images/emoticons/8.png'>",
			"<img class='emoticon' src='../images/emoticons/8.png'>",
			"<img class='emoticon' src='../images/emoticons/9.png'>",
			"<img class='emoticon' src='../images/emoticons/9.png'>",
			"<img class='emoticon' src='../images/emoticons/10.png'>",
			"<img class='emoticon' src='../images/emoticons/11.png'>",
			"<img class='emoticon' src='../images/emoticons/12.png'>",
			"<img class='emoticon' src='../images/emoticons/12.png'>",
			"<img class='emoticon' src='../images/emoticons/14.png'>",
			"<img class='emoticon' src='../images/emoticons/14.png'>");

	$queryComments="SELECT * FROM entries WHERE parent_uid='".$_POST['uid']."'";
		$resultComments=mysqli_query($con,$queryComments);
		while($rowComments=mysqli_fetch_array($resultComments))
		{
			$query2="SELECT COUNT(id) FROM marks WHERE entry_uid='".$rowComments['uid']."' AND type='plus'";
		$result2=mysqli_query($con,$query2);
		$row2=mysqli_fetch_array($result2);
		$ilplusow=$row2['COUNT(id)'];
		$query2="SELECT COUNT(id) FROM marks WHERE entry_uid='".$rowComments['uid']."' AND type='minus'";
		$result2=mysqli_query($con,$query2);
		$row2=mysqli_fetch_array($result2);
		$ilminusow=intval($row2['COUNT(id)'])*(-1);
		$resultMathComments=$ilminusow+$ilplusow;

		$query2="SELECT * FROM users WHERE id=".$rowComments['author'];
		$result2=mysqli_query($con,$query2);
		$row2=mysqli_fetch_array($result2);
		$uname=$row2['username'];
			echo "<div class='commentItself'><div class='commentMeta inlinowe'>
		<div class='authorPictureDiv'><img class='commentAuthorPicture' src='";
		if(file_exists("../images/profiles/".$rowComments['author'].".jpg"))
		{
			echo "../images/profiles/".$rowComments['author'].".jpg?dt=".date("His");
		}
		else
		{
			echo "../images/profiles/defaultprofile.png";
		}

		echo "'></div>".$uname;
		if($rowComments['link']!='')
		{
			echo "<div class='linkInEntry' onclick=\"window.open('".$rowComments['link']."','_blank')\"><img class='actionIcon inlinowe-mid' src='../images/icons/Link-100.png'><span class='inlinowe-mid'>link</span></div>";
		}

		$contencikComment = str_replace($emotikonytext,$emotikonyimages,$rowComments['content']);
		echo "</div>
		<div class='commentText inlinowe'>".$contencikComment."</div>
		<div class='commentActionsDiv'>
		<div class='commentPlusMinus inlinowe-mid'>
		<img class='inlinowe-mid commentMarkButton' src='../images/icons/Plus-100.png' onclick=\"changeMarks('".$rowComments['uid']."',1,'".$_POST['uid']."')\"><span class='inlinowe-mid commentResultSpan' id='resultSpan".$rowComments['uid']."'>".$resultMathComments."</span><img class='inlinowe-mid commentMarkButton' src='../images/icons/Minus-100.png' onclick=\"changeMarks('".$rowComments['uid']."',-1,'".$_POST['uid']."')\">
		</div><div class='rightFloatingCommentActions'>";
		if($_SESSION['userid']==$rowComments['author'])
		{
			echo "<div class='commentAuthorActions inlinowe-mid' onclick=\"deleteEntry('".$rowComments['uid']."',true,'".$rowComments['parent_uid']."')\">usuń komentarz</div>";
		}

		echo "</div></div></div>";
		}

		echo "</div>";

?>