<?php
include("config.php");
include("function.php");
//checkLogin();

//if the login form is submitted 
if (isset($_POST['submit'])) { // if form has been submitted
	// makes sure they filled it in
 	if(!$_POST['username'] | !$_POST['pass']) {
		die('Please file every required fields');
 	}

 	// checks it against the database
 	$check = mysql_query("SELECT * FROM users WHERE username = '".mysql_real_escape_string($_POST['username'])."'")or die(mysql_error());

	//Gives error if user dosen't exist
	$check2 = mysql_num_rows($check);
	
	if ($check2 == 0) {
 		die('No such person... <a href=addUser.php>please register first! </a>');
 	}

	while($info = mysql_fetch_array( $check )) {
		$_POST['pass'] = md5($_POST['pass']);
//		echo $_POST['pass']. "<BR>" .$info['password'].'<BR>';
		
		
		

		//gives error if the password is wrong
		if ($_POST['pass'] != $info['password']) {
			ErrorMessage::log2DB(ErrorMessage::$MSG_LOGIN,(mysql_real_escape_string($_POST['username'])." login failed"));
			die('<a href=login.php>Incorrect password, please try again.</a>');
		}else { 
			ErrorMessage::log2DB(ErrorMessage::$MSG_LOGIN,(mysql_real_escape_string($_POST['username'])." login successfully"));
			// if login is ok then we add a cookie 
			$hour = time() + 3600; 		
			$_SESSION['username']= $_POST['username'] ; 
			$_SESSION['ID']= $info['ID'];	 			
			//then redirect them to the members area 
			mysql_query('update users set login_counts = login_counts+1  ,login_date = NOW() where ID = '.$info['ID']);
			$arr = array ('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5);

		　　echo json_encode($arr);
			echo "";

		} 
	} 

}else{	 
	ErrorMessage::log2DB(ErrorMessage::$MSG_VISIT,$_SERVER['PHP_SELF']);
	// if they are not logged in 
?> 
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
 <body>
 <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"> 

 <table border="0"> 
	<tr><td colspan=2><h1>登入</h1></td></tr> 
	<tr><td>Username:</td>
		<td> <input type="text" name="username" maxlength="40"> </td>
		</tr> 
	<tr><td>Password:</td>
		<td><input type="password" name="pass" maxlength="30"> </td>
		</tr> 
	<tr><td colspan="2" align="right"> <input type="submit" name="submit" value="Login"> </td>
		</tr>
 </table>
 </form> 
<a href="addUser.php">還沒有帳號?</a> or <a href='forgetPassword.php'>忘記密碼</a> 
<BR><a href="https://sites.google.com/site/aboutcgearc/">團購規則(必讀)</a><BR> 

<?php 
} 
?>
<BR>


</body>
</html>