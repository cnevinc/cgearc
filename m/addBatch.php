<?php 
// Connects to your Database 
include("config.php");
include("function.php");


//This code runs if the form has been submitted
if (isset($_POST['submit'])) { 
	
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
 	$insert = "INSERT INTO order_batch (batch_no, batch_remark,createion_date,update_date,due_date)
 			VALUES (N'".$_POST['batch_no']."', 
					N'".$_POST['batch_remark']."',
					NOW(),
					NOW(),
					N'".$_POST['due_date']."'
			)";
 	$add_member = mysql_query($insert) OR die (mysql_error());

 	?>

 <h1>新增完成</h1>
回到 <a href="home.php">主頁</a><br>
到 <a href="listBatch.php">出團列表</a><br>
<?php 

}else{	

?>
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?PHP
echo "Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>";
	
?> 
必填欄位*
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border="0">
	<tr><td>團號*:</td>
		<td><input type="text" name="batch_no" maxlength="60"></td>
		</tr>
	<tr><td>備註*:</td>
		<td><input type="text" name="batch_remark" maxlength="60"></td>
		</tr>
	<tr><td>截止日期*</td>
		<td><input type="text" name="due_date" maxlength="60"></td>
		</tr>
	
	<tr><td colspan=2><input type="submit" name="submit" value="新增"></th>
		</tr> 
</table>
</form>

<?php
}

?> 
<a href=home.php>主頁</a>
 </body>
 </html>
