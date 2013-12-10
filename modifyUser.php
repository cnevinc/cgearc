<?php 
// Connects to your Database 
include("config.php");

$ERROR_MSG ;
$INFO_MSG;

//This code runs if the form has been submitted
if (isset($_POST['submit']) ) { 
	//This makes sure they did not leave any fields blank
	if (!$_POST['username']  | !$_POST['user_email'] | !$_POST['user_mobile'] | !$_POST['user_ptt'] | !$_POST['user_cname']) {
		$ERROR_MSG= '<font color=red>必填欄位未填</font>';
	}

	// this makes sure both passwords entered match
	if ($_POST['pass'] != $_POST['pass2']) {
		$ERROR_MSG=("<font color=red>密碼不符合</font>");
		//echo $_POST['pass']." ~ ".$_POST['pass2'];
	}

	$_POST['pass'] = md5($_POST['pass']);

	// now we update the user
	$sSQL = "UPDATE users SET username = N'".$_POST['username']."', ";
	if (isset($_POST['pass'])){
		$sSQL = $sSQL . "password = N'".$_POST['pass']."',";
	}				
	$sSQL = $sSQL.
					"password = N'".mysql_real_escape_string($_POST['pass'])."',
					user_cname = N'".mysql_real_escape_string($_POST['user_cname'])."',
					user_address = N'".mysql_real_escape_string($_POST['user_address'])."',
					user_mobile = N'".mysql_real_escape_string($_POST['user_mobile'])."',
					user_phone = N'".mysql_real_escape_string($_POST['user_phone'])."',
					user_email = N'".mysql_real_escape_string($_POST['user_email'])."',
					user_fb = N'".mysql_real_escape_string($_POST['user_fb'])."',
					user_ptt = N'".mysql_real_escape_string($_POST['user_ptt'])."',
					user_gpp = N'".mysql_real_escape_string($_POST['user_gpp'])."',
					update_date = NOW() WHERE ID = ".$_SESSION['ID'];
	//echo "<pre>".$sSQL."</pre>";

	
	if (!isset($ERROR_MSG)){
		mysql_query($sSQL) OR die (mysql_error());
		if (isset($_POST['pass'])){
			$_SESSION['password'] = $_POST['pass'];
			if (!mysql_error())
				$INFO_MSG = "<font color=green>修改成功</font>";
		}
	}
	
}
?>
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
必填欄位*
<?PHP
echo "Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>";
?>
<?php
echo $ERROR_MSG ."<BR>"; 
echo $INFO_MSG ."<BR>"; 
$result = mysql_query("select * from users where ID = ".$_SESSION["ID"]) or die (mysql_error());

while ($row = mysql_fetch_array($result)){
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border="1">
	<tr><td>登入用帳號*:</td>
		<td><input type="text" name="username" maxlength="60" value=<?=$row['username']?> readonly>(不可修改)</td>
		</tr>
	<tr><td>新密碼*:</td>
		<td><input type="password" name="pass" maxlength="30">(不修改可空白)</td>
		</tr>
	<tr><td>確認新密碼*:</td>
		<td><input type="password" name="pass2" maxlength="30"></td>
		</tr>
	<tr><td>中文姓名*:</td>
		<td><input type="text" name="user_cname" maxlength="60" value=<?=$row['user_cname']?> >(核對身分用)</td>
		</tr>
	<tr><td>Email*:</td>
		<td><input type="text" name="user_email" maxlength="60" value=<?=$row['user_email']?> >(核對身分用)</td>
		</tr>
	<tr><td>手機*:</td>
		<td><input type="text" name="user_mobile" maxlength="60" value=<?=$row['user_mobile']?> >(緊急連絡才打,<font color=red>匯款問題只會透過站內信</font>)</td>
		</tr>
	<tr><td>地址:</td>
		<td><input type="text" name="user_address" maxlength="60" value=<?=$row['user_address']?> >(不宅配者不需填寫)</td>
		</tr>
	<tr><td>電話:</td>
		<td><input type="text" name="user_phone" maxlength="60" value=<?=$row['user_phone']?> >(非必填)</td>
		</tr>
	<tr><td>Facebook:</td>
		<td><input type="text" name="user_fb" maxlength="60" value=<?=$row['user_fb']?> >(不想分享給朋友不需填寫)</td>
		</tr>
	<tr><td>PTT帳號*:</td>
		<td><input type="text" name="user_ptt" maxlength="60" value=<?=$row['user_ptt']?> >(目前以PTT版友為主要聯絡對象,請必填)</td>
		</tr>
	<tr><td>Google Plus 帳號:</td>
		<td><input type="text" name="user_gpp" maxlength="60" value=<?=$row['user_gpp']?> >(不想分享給朋友不需填寫)</td>
		</tr>	
	<tr><td colspan=2><input type="submit" name="submit" value="修改"></th>
		</tr> 

<?php
}
?> 
</table>
</form>

餘款增減記錄
<table border=1>
<TR><TD>交易序號</TD><TD>日期</TD><TD>金額</TD><TD>原因</TD></TR>
<?php

	$result = mysql_query("select * from user_balance where user_id = ".$_SESSION["ID"]) or die (mysql_error() ."<BR>"."select * from user_balance where user_id = ".$_SESSION["ID"]);

	while ($row = mysql_fetch_array($result)){
?>
		<TR><TD><?=$row['balance_id']?></TD><TD><?=$row['balance_date']?></TD><TD><?=$row['balance_amount']?></TD><TD><?=$row['balance_desc']?></TD></TR>

<?php
		$balance_amount= $balance_amount+$row['balance_amount'];
	}
	
	echo "<TR><TD colspan=2>Total</TD><TD>".$balance_amount."</TD><TD></TD></TR>";
	echo "</table>";

?>
<BR>回到<a href='home.php'>主頁</a>

 </body>
 </html>
