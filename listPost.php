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
<table  width=800>
<tr><td colspan=4 bgcolor='#0000A0'> <font color='#FFFFFF'><div style='float:center;' >【團員:<?=$_SESSION['username']?>】</div>   <div style='float:right;'>看板《STPGROUPON》</div>  </font> <center><font color='#FFFF00'>裝備討論區</center> </td>
<tr><td colspan=4> <a style='color:FFFFFF;' href=logout.php>[←]登出</a>][<a style='color:FFFFFF;' href=home.php>[→]回到主頁</a>][<a style='color:FFFFFF;' href='addPost.php' target=_top>[Ctrl-P]發表文章</a>]</td>
</tr>
<tr bgcolor=#FFFFFF>
<td>編號</td>
<td>日期</td>	
<td>作者</td>
<td>文章標題</td>

</tr>
<?PHP
$sSQL= "SELECT
					post.*,
					user.username
			FROM 	post_header  post
			JOIN 	users user
				ON 	post.user_id = user.ID
			WHERE	post_reply_id = 0
			ORDER BY	post_id
				";
			
$result = mysql_query($sSQL) ; // table 必須至少有一筆資料

while($row = mysql_fetch_array($result)) {
	
	echo "<tr bgcolor=000000>";
	echo "<td><font color=FFFFFF>".$row["post_id"]."</td>";
	echo "<td><font color=FFFFFF>".substr($row["update_date"],5,5)."</td>";
	echo "<td><font color=FFFFFF>".$row["username"]."</td>";
	echo "<td><font color=FFFFFF>[".$row["post_cat"]."]<a style='color:FFFFFF;' href='showPost.php?post_id=".$row["post_id"]."' >".$row["post_subject"]."</a></td>";
	echo "</tr>";

	
}
?>
</table>

<br><br>


<BR>

