<?php
include("config.php");
include("function.php");
//if ($_SESSION['username']=="")
	//$_SESSION['username']="[<a href=login.pp>登入</a>][<a href=addUser.php>註冊</a>]";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
<script type="text/javascript" src="./ckeditor/ckeditor.js"></script>
</head>
<body bgcolor=black>
<?//-------------------------顯示文章本文部份-start-----------------------?>
<table width=800 >
<?PHP
$sSQL= "SELECT
					post.*,
					user.username
			FROM 	post_header  post
			JOIN 	users user
				ON 	post.user_id = user.ID
			WHERE	post_id = '".mysql_real_escape_string($_GET['post_id'])."'	
			
				";
			
$result = mysql_query($sSQL) ; // table 必須至少有一筆資料

while($row = mysql_fetch_array($result)) {
	echo "<tr bgcolor=#0000A0>";
	echo "	<td width=10% bgcolor=#DEDEDE><font color=#0000A0>作者</font></td>
			<td width=70%><b><font color=#DEDEDE>".htmlentities($row["username"],ENT_QUOTES,'UTF-8')."</font></td>";
	echo "	<td width=10% bgcolor=#DEDEDE><font color=#0000A0>分類</font></td>
			<td width=10% ><b><font color=#DEDEDE>".htmlentities($row["post_cat"],ENT_QUOTES,'UTF-8')."</font></td>";
	echo "</tr>";
	echo "<tr bgcolor=#0000A0>
			<td bgcolor=#DEDEDE><font color=#0000A0>標題</font></td>
			<td colspan=3><b><font color=#DEDEDE>".htmlentities($row["post_subject"],ENT_QUOTES,'UTF-8')."</font></td>
		</tr>";
	echo "	<tr bgcolor=#0000A0><td bgcolor=#DEDEDE><font color=#0000A0>時間</font></td>
			<td colspan=3><b><font color=#DEDEDE>".htmlentities($row["update_date"],ENT_QUOTES,'UTF-8')."</font></td></tr>";
	echo "<tr   height=400>
			<td bgcolor=#000000 colspan=4 valign=top><pre><font color=#FFFFFF>".htmlentities($row["post_body"],ENT_QUOTES,'UTF-8')."</font></pre></td>
		</tr>";
	echo "<tr>
			<td bgcolor=#DEDEDE colspan=4>[<a href='addPost.php' target=_top>發表文章</a>][<a href='?post_id=".$row["post_id"]."&post_reply_id=".$row["post_id"]."'>回覆文章</a>][<a href='listPost.php'>文張列表</a>]</td></tr>";
	
	

	
}
?>

<?//-------------------------顯示文章本文部份-end-----------------------?>


<?//-------------------------顯示回覆文章部份-start-----------------------?>

<?PHP
if ($_GET['post_id']<>""){
	$sSQL= "SELECT
						post.*,
						user.username
				FROM 	post_header  post
				JOIN 	users user
					ON 	post.user_id = user.ID
				WHERE	post_reply_id = ".mysql_real_escape_string($_GET['post_id'])."
					";
				
	$result = mysql_query($sSQL) ; // table 必須至少有一筆資料

	while($row = mysql_fetch_array($result)) {
		echo "<tr bgcolor=000000>
				<td><b><font color=red>-></font><font color=FFFF32>".htmlentities($row["username"],ENT_QUOTES,'UTF-8')."</b></td>";
		echo "<td ><font color=CDCD00><pre>".htmlentities($row["post_body"],ENT_QUOTES,'UTF-8')."</pre></td>";
		echo "<td  colspan=2><font color=FFFFFF>".htmlentities($row["update_date"],ENT_QUOTES,'UTF-8')."</font></td></tr>";
		
		

		
	}
}
?>
<?//-------------------------顯示回覆文章部份-end-----------------------?>



<br><br>

<?php
// ------------------------新增文章部分----------------------------start
if ($_GET['post_reply_id']<>""){
?>
<tr style="background-color:#000000;">
	<td colspan=4>
	<form action=addPostHandler.php method=post>
	<input type='hidden' name='post_reply_id' value='<?=$_GET['post_reply_id']?>'>
	<input type='hidden' name='post_cat' value='reply'>
	<input type='hidden' name='post_subject' value='reply'>
	<textarea name="post_body" rows="10" cols="80" style="background-color:#000000;color:#FFFFFF;" ></textarea>
	<input type=submit value='回覆文章'>
	</form>
	</td></tr>



<?php
}
// ------------------------新增文章部分----------------------------end
?>
</table>

<BR>