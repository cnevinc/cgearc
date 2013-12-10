<?php 
// Connects to your Database 
include("config.php");
include("function.php");



//This code runs if the form has been submitted
if (isset($_POST['submit']) ) { 
	//This makes sure they did not leave any fields blank
	foreach($_POST as $key => $value){
		if ($value == ""){
			//die('You did not complete all of the required fields');
		}
	}

	// now we update the user
	$sSQL = "UPDATE order_batch SET batch_bill_ntd = N'".$_POST['batch_bill_ntd']."', 
			batch_fee_ntd = N'".$_POST['batch_fee_ntd']."',
			batch_customs_ntd = N'".$_POST['batch_customs_ntd']."',
			batch_bill_usd = N'".$_POST['batch_bill_usd']."',
			batch_freight_usd = N'".$_POST['batch_freight_usd']."',
			batch_remark = N'".$_POST['batch_remark']."',
			due_date = N'".$_POST['due_date']."',
			batch_no = N'".$_POST['batch_no']."',
			update_date = NOW() WHERE batch_id = N'".$_POST['batch_id']."'";
	//echo "<pre>".$sSQL."</pre>";

	
	if (!isset($ERROR_MSG)){
		mysql_query($sSQL) OR die (mysql_error());
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
必填欄位*
<?php
echo $ERROR_MSG ."<BR>"; 
$result = mysql_query("select * from order_batch ") or die (mysql_error());

while ($row = mysql_fetch_array($result)){
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="batch_id" maxlength="60" value='<?=$row['batch_id']?>' readonly>
<table border="1">
	<tr><td>團號*:</td>
		<td><input type="text" name="batch_no" maxlength="60" value='<?=$row['batch_no']?>' ></td>
		</tr>
	<tr><td>刷卡總金額*:</td>
		<td><input type="text" name="batch_bill_ntd" maxlength="10" value='<?=$row['batch_bill_ntd']?>' ></td>
		</tr>
	<tr><td>刷卡手續費*:</td>
		<td><input type="text" name="batch_fee_ntd" maxlength="10" value='<?=$row['batch_fee_ntd']?>' ></td>
		</tr>
	<tr><td>關稅分攤基準*:</td>
		<td><input type="text" name="batch_customs_ntd" maxlength="60" value='<?=$row['batch_customs_ntd']?>' ></td>
		</tr>
	<tr><td>刷卡美金*:</td>
		<td><input type="text" name="batch_bill_usd" maxlength="60" value='<?=$row['batch_bill_usd']?>' ></td>
		</tr>
	<tr><td>運費美金*:</td>
		<td><input type="text" name="batch_freight_usd" maxlength="60" value='<?=$row['batch_freight_usd']?>' ></td>
		</tr>
	<tr><td>備註*:</td>
		<td><input type="text" name="batch_remark" maxlength="60" value='<?=$row['batch_remark']?>' ></td>
		</tr>
	<tr><td>截止日期*</td>
		<td><input type="text" name="due_date" maxlength="60" value='<?=$row['due_date']?>' ></td>
		</tr>
	
	<tr><td colspan=2><input type="submit" name="submit" value="修改"></th>
		</tr> 
</table>
</form>

<?php
}
?> 
回到<a href='home.php'>主頁</a>
 </body>
 </html>
