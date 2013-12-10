<?php 
// Connects to your Database 
include("config.php");

//This code runs if the form has been submitted
if (isset($_POST['submit'])) { 
	
	//foreach($_POST as $key => $value){ echo "$key => ".($value) ."<BR>";}
	
	//This makes sure they did not leave any fields blank
	if (!$_POST['username'] | !$_POST['pass'] | !$_POST['pass2'] | !$_POST['user_email'] | !$_POST['user_mobile'] | !$_POST['user_ptt'] | !$_POST['user_cname']) {
 		die('You did not complete all of the required fields');
 	}

	$usercheck = $_POST['username'];
	$check = mysql_query("SELECT username FROM users WHERE username = '$usercheck'") or die("Can't Select User Name");
	$check2 = mysql_num_rows($check);
	
	//if the name exists it gives an error
	if ($check2 != 0) {
 		die('Sorry, the username '.$_POST['username'].' is already in use.');
	}

	// this makes sure both passwords entered match
 	if ($_POST['pass'] != $_POST['pass2']) {
 		die('Your passwords did not match. ');
 	}
	
 	$_POST['pass'] = md5($_POST['pass']);

	// now we insert it into the database
 	$usSQL = "INSERT INTO users (username, password,user_cname,user_address,user_mobile,user_phone,user_email,user_fb,user_ptt,user_gpp,login_counts,login_date,creation_date,update_date)
 			VALUES (N'".$_POST['username']."', 
					N'".mysql_real_escape_string($_POST['pass'])."',
					N'".mysql_real_escape_string($_POST['user_cname'])."',
					N'".mysql_real_escape_string($_POST['user_address'])."',
					N'".mysql_real_escape_string($_POST['user_mobile'])."',
					N'".mysql_real_escape_string($_POST['user_phone'])."',
					N'".mysql_real_escape_string($_POST['user_email'])."',
					N'".mysql_real_escape_string($_POST['user_fb'])."',
					N'".mysql_real_escape_string($_POST['user_ptt'])."',
					N'".mysql_real_escape_string($_POST['user_gpp'])."',
					N'".($_POST['login_counts']+1)."',
					NOW(),
					NOW(),
					NOW()
			)";
 	echo "<pre> $usSQL </pre>";
	$add_member = mysql_query($usSQL) OR die (mysql_error());

 	?>

 <h1>註冊完成</h1>

 <p>請重新 <a href="login.php">登入</a>.</p>
<?php 

}else{	

?>
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
 <body>
 必填欄位*
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border="0">
	<tr><td>登入用帳號*:</td>
		<td><input type="text" name="username" maxlength="60">(請與PTT帳號相同)</td>
		</tr>
	<tr><td>密碼*:</td>
		<td><input type="password" name="pass" maxlength="30"></td>
		</tr>
	<tr><td>確認密碼*:</td>
		<td><input type="password" name="pass2" maxlength="30"></td>
		</tr>
	<tr><td>中文姓名*:</td>
		<td><input type="text" name="user_cname" maxlength="60">(核對身分用)</td>
		</tr>
	<tr><td>Email*:</td>
		<td><input type="text" name="user_email" maxlength="60">(核對身分用)</td>
		</tr>
	<tr><td>手機*:</td>
		<td><input type="text" name="user_mobile" maxlength="60">(緊急連絡才打,<font color=red>匯款問題只會透過站內信</font>)</td>
		</tr>
	<tr><td>地址:</td>
		<td><input type="text" name="user_address" maxlength="60">(不宅配者不需填寫)</td>
		</tr>
	<tr><td>電話:</td>
		<td><input type="text" name="user_phone" maxlength="60">(非必填)</td>
		</tr>
	<tr><td>Facebook:</td>
		<td><input type="text" name="user_fb" maxlength="60">(不想分享給朋友不需填寫)</td>
		</tr>
	<tr><td>PTT帳號*:</td>
		<td><input type="text" name="user_ptt" maxlength="60" >(目前以PTT版友為主要聯絡對象,請必填)</td>
		</tr>
	<tr><td>Google Plus 帳號:</td>
		<td><input type="text" name="user_gpp" maxlength="60">(不想分享給朋友不需填寫)</td>
		</tr>	
	<tr><td colspan=2><input type="submit" name="submit" value="Register"></th>
		</tr> 
</table>
</form>

<?php
}

?> 
 </body>
 </html>
