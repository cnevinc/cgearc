<?php 
// Connects to your Database 
include("config.php");
$_SESSION['username']= null ; 
$_SESSION['ID']= null ; 
$ERROR_MSG ;
$INFO_MSG;

//This code runs if the form has been submitted
if (isset($_POST['submit']) ) { 
	//This makes sure they did not leave any fields blank
	if (!$_POST['username']  | !$_POST['user_email'] | !$_POST['user_mobile'] | !$_POST['user_ptt'] | !$_POST['user_cname']) {
		//$ERROR_MSG= '<font color=red>必填欄位未填</font>';
	}

	// this makes sure both passwords entered match
	if ($_POST['pass'] != $_POST['pass2']) {
		$ERROR_MSG=("<font color=red>密碼不符合</font>");
		//echo $_POST['pass']." ~ ".$_POST['pass2'];
	}
	
	$sSQL = "SELECT * FROM users
		WHERE 		username = N'".mysql_real_escape_string($_POST['username'])."'
			AND	user_cname = N'".mysql_real_escape_string($_POST['user_cname'])."'
			AND	user_ptt = N'".mysql_real_escape_string($_POST['user_ptt'])."'
			AND	user_email = N'".mysql_real_escape_string($_POST['user_email'])."'
			AND	user_mobile = N'".mysql_real_escape_string($_POST['user_mobile'])."'
			";
	//echo "<pre>".$sSQL."</pre>";

	// checks it against the database
 	$check = mysql_query($sSQL)or die(mysql_error());

	//Gives error if user dosen't exist
	$check2 = mysql_num_rows($check);
	
	if ($check2 == 0) {
 		die('使用者不存在or資料錯誤. <a href=addUser.php>請點此註冊</a> <BR>若忘記個人資訊,請用本尊PTT ID站內信給我');
 	}else{
		//echo "you are";
	}
	
	$_POST['pass'] = md5($_POST['pass']);

	// now we update the user
	$sSQL = "UPDATE users SET 
			password = N'".$_POST['pass']."',
			update_date = NOW() 
		WHERE 		username = N'".mysql_real_escape_string($_POST['username'])."'
			AND	user_cname = N'".mysql_real_escape_string($_POST['user_cname'])."'
			AND	user_ptt = N'".mysql_real_escape_string($_POST['user_ptt'])."'
			AND	user_email = N'".mysql_real_escape_string($_POST['user_email'])."'
			AND	user_mobile = N'".mysql_real_escape_string($_POST['user_mobile'])."'
			";
	//echo "<pre>".$sSQL."</pre>";

	
	if (!isset($ERROR_MSG)){
		$r= mysql_query($sSQL) OR die (mysql_error());
		if (isset($_POST['pass'])){
			if (!mysql_error()){
				$INFO_MSG = "修改成功";
			}
		}
	}
	
	
}
?>
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?php
echo $ERROR_MSG ."<BR>"; 
echo $INFO_MSG ."<BR>"; 

?>
忘記密碼
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border="0">
	<tr><td>原帳號*:</td>
		<td><input type="text" name="username" maxlength="60" ></td>
		</tr>
	<tr><td>中文姓名*:</td>
		<td><input type="text" name="user_cname" maxlength="60"></td>
		</tr>
	<tr><td>原申請Email*:</td>
		<td><input type="text" name="user_email" maxlength="60" ></td>
		</tr>
	<tr><td>原申請手機*:</td>
		<td><input type="text" name="user_mobile" maxlength="60"></td>
		</tr>
	<tr><td>原申請PTT帳號*:</td>
		<td><input type="text" name="user_ptt" maxlength="60"></td>
		</tr>
	<tr><td>新密碼*:</td>
		<td><input type="password" name="pass" maxlength="30"></td>
		</tr>
	<tr><td>確認新密碼*:</td>
		<td><input type="password" name="pass2" maxlength="30"></td>
		</tr>
	<tr><td colspan=2><input type="submit" name="submit" value="修改"></th>
		</tr> 
</table>
</form>
重新<a href='login.php'>登入</a>
<?php

?> 
 </body>
 </html>
