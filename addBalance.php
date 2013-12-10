<?php 
// Connects to your Database 
include("config.php");
include("function.php");
if (!isAdmin()){
echo "Not allowed";
exit;
}
?>

<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?php
//This code runs if the form has been submitted
if (isset($_POST['submit'])) { 
	
	
	//This makes sure they did not leave any fields blank
	foreach($_POST as $key => $value){
		if ($value == ""){
			die('You did not complete all of the required fields');
		}
	}
	


	// now we insert it into the database
 	$insert = "INSERT INTO user_balance ( `user_id` ,`balance_amount` ,`balance_desc` ,`balance_date`)
 			VALUES (N'".$_POST['user_id']."', 
					N'".$_POST['balance_amount']."',
					N'".$_POST['balance_desc']."',
					NOW()
			)";
 	$add_member = mysql_query($insert) OR die (mysql_error());
	
 	?>
<table border=1>
<TR><TD>交易序號</TD><TD>日期</TD><TD>金額</TD><TD>原因</TD></TR>
<?php
	echo "History~<BR>"; 
	$result = mysql_query("select * from user_balance where user_id = ".$_POST['user_id']) or die (mysql_error() ."<BR>"."select * from user_balance where user_id = ".$_GET['user_id']);

	while ($row = mysql_fetch_array($result)){
?>
	<TR><TD><?=$row['balance_id']?></TD><TD><?=$row['balance_date']?></TD><TD><?=$row['balance_amount']?></TD><TD><?=$row['balance_desc']?></TD></TR>

<?php
	}
		echo "</table>";
?>
 <h1>新增完成</h1>

<?php 

}else{	

?>

<?PHP
echo "Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>";
$result = mysql_query("select sum(balance_amount) as balance_total , max(username) as name from user_balance join users on users.ID = user_balance.user_id where user_id = ".$_GET['user_id'] ) or die (mysql_error());
$row = mysql_fetch_array($result);
?> 

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border="1">
	<tr><td>使用者</td>
		<td><?=$row['name']?><input type="text" name="user_id" maxlength="5" value="<?=$_GET['user_id']?>" ></td>
		</tr>
	<tr><td>目前餘額</td>
		<td><?=$row['balance_total']?></td>
		</tr>
	<tr><td>改變數量</td>
		<td><input type="text" name="balance_amount" maxlength="60"></td>
		</tr>
	<tr><td>改變原因</td>
		<td><input type="text" name="balance_desc" maxlength="60"></td>
		</tr>
	
	<tr><td colspan=2><input type="submit" name="submit" value="新增"></th>
		</tr> 
</table>
</form>
History~~
<table border=1>
<TR><TD>交易序號</TD><TD>日期</TD><TD>金額</TD><TD>原因</TD></TR>
<?php

	$result = mysql_query("select * from user_balance where user_id = ".$_GET['user_id']) or die (mysql_error() ."<BR>"."select * from user_balance where user_id = ".$_GET['user_id']);

	while ($row = mysql_fetch_array($result)){
?>
	<TR><TD><?=$row['balance_id']?></TD><TD><?=$row['balance_date']?></TD><TD><?=$row['balance_amount']?></TD><TD><?=$row['balance_desc']?></TD></TR>

<?php
	}
	echo "</table>";
}


?> 
<a href=home.php>回到主頁</a>
 </body>
 </html>
