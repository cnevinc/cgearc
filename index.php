<?php
include("config.php");
include("function.php");

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
			//ErrorMessage::log2DB(ErrorMessage::$MSG_LOGIN,(mysql_real_escape_string($_POST['username'])." login failed"));
			die('<a href=login.php>Incorrect password, please try again.</a>');
		}else { 
			//ErrorMessage::log2DB(ErrorMessage::$MSG_LOGIN,(mysql_real_escape_string($_POST['username'])." login successfully"));
			// if login is ok then we add a cookie 
			$hour = time() + 3600; 		
			$_SESSION['username']= $_POST['username'] ; 
			$_SESSION['ID']= $info['ID'];	 			
			//then redirect them to the members area 
			mysql_query('update users set login_counts = login_counts+1  ,login_date = NOW() where ID = '.$info['ID']);
			header("Location: home.php"); 

		} 
	} 

}else{	 
	//ErrorMessage::log2DB(ErrorMessage::$MSG_VISIT,$_SERVER['PHP_SELF']);
	// if they are not logged in 
			
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<script src="http://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
<h1>CGEARC.COM</h1>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"> 

 <table border="0"> 
	
	<tr><td>Username:</td>
		<td> <input type="text" name="username" maxlength="40"> </td>
		<td>Password:</td>
		<td><input type="password" name="pass" maxlength="30"> </td>
		<td colspan="2" align="right"> <input type="submit" name="submit" value="Login"> </td>
		<td><a href="addUser.php">還沒有帳號?</a></td>
		<td><a href='forgetPassword.php'>忘記密碼</a> </td>
		</tr>
 </table>
 </form> 

<HR>
<a href="?clause=Jacket">[外套]</a>&nbsp;<a href="?clause=Shirt">[上衣]</a>&nbsp;<a href="?clause=base">[內層]</a>&nbsp;
<a href="?clause=Pants">[褲]</a>&nbsp;<a href="?clause=Hat">[帽子]</a>&nbsp;
<a href="?clause=Socks">[襪]</a>&nbsp;&nbsp;<a href="?clause=Boots">[靴]</a>&nbsp;<a href="?clause=Shoes">[鞋]</a>&nbsp;
<a href="?clause=Backpack">[包]</a>&nbsp;<a href="?clause=Gore">[Gore-Tex®]</a>&nbsp; <a href="?clause=Shell">[Soft Shell]</a>&nbsp; 
<a href="?clause=Shell">[Soft Shell]</a>&nbsp; <a href="?clause=Hardwear">[Mountain Hardwea]</a>&nbsp; <a href="?clause=Sleeping">[睡袋]</a>&nbsp; 
 
<a href="?clause=">[都看看]</a>&nbsp;  
<a href="?action=watchHot">[最多人買]</a>&nbsp;  
<a href="?action=review">[有人評價]</a>&nbsp;  <font color=red>New!</font>

<form action=addQuotation.php method=post>



<table border=1>
 <tr>
	<td>我也要買</td>
	<td>STP Item No</td>
	<td>產品名稱</td>
	<td>顏色</td>	
	<td>大小</td>	
	<td>特殊規格</td>	
	<td>STP網址</td>
	<td>商品數量</td>
	<td>報價(USD)</td>
	<td>評價</td>
	
 </tr>
 <?PHP
	$sSQL = "SELECT order_line.*
			FROM 	order_line
		
			WHERE header_id <>0 
			AND stp_item_name like '%".mysql_real_escape_string($_GET['clause'])."%'
			ORDER BY stp_item_name ";
	
	if ($_GET['action']=="watchHot"){
		$sSQL = "SELECT order_line.* 
				FROM 	order_line
				JOIN (select stp_item_name ,
						count(stp_item_name) as count from order_line where header_id <>0  group by stp_item_name having count(stp_item_name)>3) hot
						ON hot.stp_item_name = order_line.stp_item_name
				WHERE header_id <> 0 
				ORDER BY stp_item_name 
				";
	
	}
	if ($_GET['action']=="review"){
		$sSQL = "SELECT order_line.* 
				FROM 	order_line
				WHERE review !=''
				ORDER BY stp_item_name 
				";
	
	}
	
	//echo $sSQL;
	$result = mysql_query($sSQL) or die (mysql_error()) ; // table 必須至少有一筆資料

	while($row = mysql_fetch_array($result)) {
		$i++;
		echo "<tr>";
		echo "<td><input type=checkbox name=refLine_id[] value=".$row["line_id"]."></td>";
		echo "<td>".$row["stp_item_no"]."</td>";
		echo "<td>".$row["stp_item_name"]."</td>";
		echo "<td>".$row["stp_color"]."</td>";
		echo "<td>".$row["stp_size"]."</td>";
		echo "<td>".$row["stp_spec"]."</td>";
		echo "<td><a href='".$row["stp_url"]."' target=blank>開啟</a></td>";
		echo "<td>".$row["stp_item_count"]."</td>";
		echo "<td>".$row["quot_amount"]."</td>";
		echo "<td>".htmlentities($row["review"],ENT_QUOTES,'utf-8')."</td>";
		echo "</tr>";
		
		
	}
 ?>
 </table>

 </form>
 <BR>

 <?php 
} 
?>