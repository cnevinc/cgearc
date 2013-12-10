<?php 
// Connects to your Database 
include("config.php");
include("function.php");

$ERROR_MSG ;
$INFO_MSG;

//This code runs if the form has been submitted
if (isset($_POST['reset']) ) { 
	
	$sSQL = "UPDATE users SET 
			password = N'".(md5($_POST['username']))."',
			update_date = NOW() 
		WHERE ID = ".mysql_real_escape_string(($_POST['ID']));
	//echo "<pre>".$sSQL."</pre>";

	mysql_query($sSQL) OR die (mysql_error());
	if (!mysql_error()){
		$INFO_MSG = "<font color=green>修改成功</font>";
	} else{
		$INFO_MSG = mysql_error();
	}
}	
?>
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?PHP
echo "Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>";
	
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
用戶列表<BR>
查詢ID <input type=text name =qusername value=<?=$_POST['qusername']?> > 
查詢中文姓名<input type=text name =quser_cname  value=<?=$_POST['quser_cname']?> >
查詢Email<input type=text name =quser_email  value=<?=$_POST['quser_email']?> >
查詢手機<input type=text name =quser_mobile  value=<?=$_POST['quser_mobile']?> >
<input type=submit name=query>
</form>

<table border="1">
<tr>
	<td>登入用帳號*:</td>
	<td>新密碼*:</td>
	<td>中文姓名*:</td>	
	<td>Email*:</td>
	<td>手機*:</td>
	<td>地址:</td>
	<td>PTT帳號*:</td>
	<td>登入日期:</td>
	<td>登入次數:</td>
</tr> 
<?php
//echoAll($_SESSION);


echo $ERROR_MSG ."<BR>"; 
echo $INFO_MSG ."<BR>"; 
$sSQL = "select * from users WHERE 1=1 ";
if ($_POST['qusername']){
	$sSQL = $sSQL . " AND username like '%".$_POST['qusername']."%'";
}
if ($_POST['quser_cname']){
	$sSQL = $sSQL . " AND user_cname like '%".$_POST['quser_cname']."%'";
}
if ($_POST['quser_mobile']){
	$sSQL = $sSQL . " AND user_mobile like '%".$_POST['quser_mobile']."%'";
}
if ($_POST['quser_email']){
	$sSQL = $sSQL . " AND user_email like '%".$_POST['quser_email']."%'";
}
$result = mysql_query($sSQL) or die (mysql_error());
echo "一共有[".mysql_num_rows($result)."]個結果";
while ($row = mysql_fetch_array($result)){
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type=hidden name=username value=<?=$row['username']?>>
<input type=hidden name=ID value=<?=$row['ID']?>>
<tr>
	<td><?=$row['ID']?>-<?=$row['username']?></td>
	<td><input type=submit value=重設密碼 name=reset></td>
	<td><?=$row['user_cname']?></td>
	<td><?=$row['user_email']?></td>
	<td><?=$row['user_mobile']?></td>
	<td><?=$row['user_address']?></td>
	<td><?=$row['user_ptt']?></td>
	<td><?=$row['login_date']?> </td>
	<td><?=$row['login_counts']?> </td>
</tr> 
</form>


<?php
}
?> 
</table>
回到<a href='home.php'>主頁</a>

 </body>
 </html>
