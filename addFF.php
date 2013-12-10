<?php 
// Connects to your Database 
include("config.php");
include("function.php");
isLoggedIn();

//This code runs if the form has been submitted
if (isset($_POST['addFF'])) { 
	
	//This makes sure they did not leave any fields blank
	foreach($_POST as $key => $value){
		if ($value == ""){
			die('You did not complete all of the required fields');
		}
	}
	
	$batch_no = $_POST['batch_no'];
	$check = mysql_query("SELECT batch_no FROM order_batch WHERE batch_no = '$batch_no'") or die("Can't Select User Name");
	$check2 = mysql_num_rows($check);
	
	//if the name exists it gives an error
	if ($check2 != 0) {
 		die('Sorry, the batch_no '.$_POST['batch_no'].' is already in use.');
	}
 	
	

	// now we insert it into the database
 	$insert = "INSERT INTO order_face2face (ff_date,ff_locate)
 			VALUES ('".$_POST['ff_date']."','".$_POST['ff_locate']."'
			)";
 	$add_member = mysql_query($insert) OR die (mysql_error());


}
?>
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
 <body>
<?php
if (isAdmin()){
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border="1">
	<tr><td>日期:</td>
		<td><input type="text" name="ff_date" value='2012-04-30 12:00:00'></td>
		<td>地點:</td>
		<td><select name=ff_locate>
			<option value=梅花戲院>梅花戲院</option>
			<option value=古亭站星巴克>古亭站星巴克</option>
			<option value=古亭站派克雞排>古亭站派克雞排</option>
			<option value=新北市八里區頂寮二街30號>新北市八里區頂寮二街30號</option>
			</select>
		</td>
		<td><input type="submit" name="addFF" value="新增"></th>
		</tr> 
</table>
</form>

<?php

}
//This code runs if the form has been submitted
if (isset($_POST['updateFF']) ) { 
	//This makes sure they did not leave any fields blank
	foreach($_POST as $key => $value){
		if ($value == ""){
			//die('You did not complete all of the required fields');
		}
	}

	// now we update the user
	$sSQL = "UPDATE order_face2face SET user_id = N'".$_POST['user_id']."'
			 WHERE ff_id =".$_POST['ff_id']."";
	//echo "<pre>".$sSQL."</pre>";

	
	if (mysql_num_rows(mysql_query("select * from order_face2face where user_id =".$_POST['user_id']))==0){
		mysql_query($sSQL) OR die (mysql_error());
	}else{
		$sSQL2 = "UPDATE order_face2face SET user_id = null
			 WHERE user_id = ".$_POST['user_id'];
		mysql_query($sSQL2) OR die (mysql_error());
		mysql_query($sSQL) OR die (mysql_error());
		echo "<h1>ㄟ,就說一個人只能掛號一次啦,把您之前的掛號取消換這個囉</h1>";
	}
	
}

?>
一個人只能掛號一次喔
<table border="1">
	<tr><td>面交批號:</td><td>面交日期:</td><td>面交地點:</td><td>面交人:</td><td>備註:</td></tr>
<?php
echo $ERROR_MSG ."<BR>"; 
$SQL ="select f.*,u.username 
	from 	order_face2face f
	left join 		users u on  f.user_id = u.ID
	order by f.ff_date
	";
	//2012-04-28 19:00:00
$result = mysql_query($SQL) or die (mysql_error());

while ($row = mysql_fetch_array($result)){
?>
<tr>
	<td>	<?=$row['ff_id']?></td>
	<td>	<?=$row['ff_date']?></td>
	<td>	<?=$row['ff_locate']?></td>
	<td>
<?php
	if ($row['user_id']==""){
		echo "<form action='' method=post><input type=hidden name=ff_id value=".$row['ff_id'].">";
		echo "<input type=hidden name=user_id value=".$_SESSION['ID']."><input type=submit name=updateFF value=我要報名></form>";
	}else{
		echo $row['username'];
	}
?>	</td>
	<td>	<?=$row['ff_remark']?>&nbsp;</td>
</tr>


<?php
}

?> 
</table>

<a href=home.php>主頁</a>
 </body>
 </html>
