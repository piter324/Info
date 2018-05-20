<?php
	session_start();
	$config_array = parse_ini_file("../../config_info.ini");
	$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
	if(!$con)
	{
		die("Connection to MySQL Database failed");
	}
	$con->set_charset("utf8");

	$uid=uniqid();

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

	$query1="SELECT * FROM entries WHERE scope='".$_POST['scope']."' AND parent_uid='' ORDER BY id DESC";
	if(isset($_POST['upd']))
	{
		$query1="SELECT * FROM entries WHERE uid='".$_POST['scope']."'";
	}
	//echo $query;
	$result1=mysqli_query($con,$query1);
	while($row=mysqli_fetch_array($result1))
	{
		$query2="SELECT COUNT(id) FROM marks WHERE entry_uid='".$row['uid']."' AND type='plus'";
		$result2=mysqli_query($con,$query2);
		$row2=mysqli_fetch_array($result2);
		$ilplusow=$row2['COUNT(id)'];
		$query2="SELECT COUNT(id) FROM marks WHERE entry_uid='".$row['uid']."' AND type='minus'";
		$result2=mysqli_query($con,$query2);
		$row2=mysqli_fetch_array($result2);
		$ilminusow=intval($row2['COUNT(id)'])*(-1);
		$result=$ilminusow+$ilplusow;

		$query2="SELECT * FROM users WHERE id=".$row['author'];
		$result2=mysqli_query($con,$query2);
		$row2=mysqli_fetch_array($result2);
		$uname=$row2['username'];

		$query2="SELECT COUNT(id) FROM entries WHERE parent_uid='".$row['uid']."'";
		$result2=mysqli_query($con,$query2);
		$row2=mysqli_fetch_array($result2);
		$comments=$row2['COUNT(id)'];

		if(!isset($_POST['upd']))
		{	
			echo "<div class='entryItself' id='entry".$row['uid']."'>";
		}

		echo "<div class='entryMeta inlinowe'>
		<div class='authorPictureDiv'><img class='authorPicture' src='";
		if(file_exists("../images/profiles/".$row['author'].".jpg"))
		{
			echo "../images/profiles/".$row['author'].".jpg?dt=".date("His");
		}
		else
		{
			echo "../images/profiles/defaultprofile.png";
		}

		echo "'></div>".$uname;
		if($row['link']!='')
		{
			echo "<div class='linkInEntry' onclick=\"window.open('".$row['link']."','_blank')\"><img class='actionIcon inlinowe-mid' src='../images/icons/Link-100.png'><span class='inlinowe-mid'>link</span></div>";
		}
			
		$contencik = str_replace($emotikonytext,$emotikonyimages,$row['content']);
		echo "</div>
		<div class='entryText inlinowe'>".$contencik."</div>
		<div class='entryActions'>
		<div class='entryPlusMinus inlinowe-mid'>
		<img class='inlinowe-mid markButton' src='../images/icons/Plus-100.png' onclick=\"changeMarks('".$row['uid']."',1)\"><span class='inlinowe-mid resultSpan' id='resultSpan".$row['uid']."'>".$result."</span><img class='inlinowe-mid markButton' src='../images/icons/Minus-100.png' onclick=\"changeMarks('".$row['uid']."',-1)\">
		</div><div class='entryCommentsCounter inlinowe-mid' id='commentCounter".$row['uid']."'>komentarzy: ".$comments."</div><div class='rightFloatingActions'>";
		if($_SESSION['userid']==$row['author'])
		{
			echo "<div class='authorActions inlinowe-mid' onclick=\"deleteEntry('".$row['uid']."',false)\">usuń wpis</div>";
		}

		echo "<div class='commentActions inlinowe-mid' id='commentActions".$row['uid']."' onclick=\"showComments('".$row['uid']."')\">pokaż komentarze</div></div></div>
		<div class='entryComments' id='comments".$row['uid']."'>
		<div class='addComment'>
		<textarea class='newCommentContent' id='newCommentContent".$row['uid']."' placeholder='dodaj komentarz'></textarea>
		<div><button class='smallButton addCommentLinkButton' onclick=\"showAddLinkCommentTextbox('".$row['uid']."')\"><img src='../images/icons/Add Link-100.png' class='actionIcon inlinowe-mid'><span class='inlinowe-mid'>dodaj link do komentarza</span></button><input type='text' class='newCommentLinkText' id='newCommentTextbox".$row['uid']."' placeholder='tu wklej adres linku, który chcesz dodać do komentarza'><button class='smallButton addCommentButton' onclick=\"postComment('".$row['uid']."')\">Zamieść komentarz</button></div><div id='commentsList".$row['uid']."'>";

		$queryComments="SELECT * FROM entries WHERE parent_uid='".$row['uid']."'";
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
		<img class='inlinowe-mid commentMarkButton' src='../images/icons/Plus-100.png' onclick=\"changeMarks('".$rowComments['uid']."',1,'".$row['uid']."')\"><span class='inlinowe-mid commentResultSpan' id='resultSpan".$rowComments['uid']."'>".$resultMathComments."</span><img class='inlinowe-mid commentMarkButton' src='../images/icons/Minus-100.png' onclick=\"changeMarks('".$rowComments['uid']."',-1,'".$row['uid']."')\">
		</div><div class='rightFloatingCommentActions'>";
		if($_SESSION['userid']==$rowComments['author'])
		{
			echo "<div class='commentAuthorActions inlinowe-mid' onclick=\"deleteEntry('".$rowComments['uid']."',true,'".$rowComments['parent_uid']."')\">usuń komentarz</div>";
		}

		echo "</div></div></div>";
		}

		echo "</div></div></div>
		</div>";
		if(!isset($_POST['upd']))
		{	
			echo "</div>";
		}
	}