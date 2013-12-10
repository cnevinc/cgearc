<?php
include("config.php");
include("function.php");
isLoggedIn();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
<script type="text/javascript" src="./ckeditor/ckeditor.js"></script>
</head>
<body>
<?="Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>][<a href=listPost.php>文章列表</a>]<BR>" ?>
<BR>
<?=$_SESSION['Message']?>
<?php $_SESSION['Message']="";?>

<?php
// ------------------------新增文章部分----------------------------start
if ($_GET['line_id']==""){
?>
<form action=addPostHandler.php method=post>
<font color=red>紅色欄位必填</font><input type='hidden' name='post_reply_id' value='0'>
<table border=1 width=800>
	<tr><td><font color=red>文章類型</font></td><td><select name='post_cat'>
											<? if (isAdmin()){?>
													<option value='公告'>公告</option>
											<? }?>
													<option value='心得'>心得</option>
													<option value='二手'>二手</option>
													<option value='建議'>建議</option>
													<option value='地雷'>地雷</option>
													<option value='求助'>求助</option></select></td></tr>
	<tr><td><font color=red>文章標題</font></td><td><input type=text name='post_subject' maxlength=250 size=50></td></tr>
	<tr><td><font color=red>文章內容</font></td><td>注意! 請勿在此處填寫個人資料,如手機,信箱,帳號,身分證號.個人資料請私下Email或是用拍賣系統傳遞<BR>
			<textarea rows="10" cols="80" name="post_body"></textarea></td></tr>
	<tr><td colspan=2><input type=submit value=新增文章></td></tr>
</table>

</form>
<?php
}
// ------------------------新增文章部分----------------------------end
?>

<BR>

<a href="home.php">回到主頁</a><br>
<a href="fleaMarket.php">跳蚤市場</a><br>
