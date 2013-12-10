<?php
include("config.php");
include("function.php");
isLoggedIn();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" /></head>
<body>
<form action =editOrderInfo.php method=post>
<?="Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>" ?>

<?php
	// 確定request是從上一頁來的
	// 確定此session的user是誰
	
	if ($_POST['selectedItems'] == null){
		echo "請選擇要加入訂單的物品<BR>";
		echo "<a href='addOrder.php'>回到新增商品</a>";
		exit;
	}
	
	// if there's a order opened , get the header_id
	$sSQL = "SELECT header_id ,order_no FROM order_header header where user_id =  ".$_SESSION['ID']."  and order_status = 0 ";
	$result = mysql_query($sSQL) or die (mysql_error());
	$row = mysql_fetch_assoc($result);
	$header_id= 0;
	if (mysql_num_rows($result)>0){
		// update selected lines and set header id
		echo "已將商品加入訂單 : " . $row['header_id']."";
		$header_id = $row['header_id'];	
		 
	}else{
		$batch = mysql_fetch_assoc(mysql_query("select max(batch_id) mb_id from order_batch "));
		
		// or create a new order header
		$sSQL = "INSERT INTO  order_header ( user_id , order_status,createion_date, batch_id ) VALUES ( ".$_SESSION['ID'].",0,NOW(),".$batch['mb_id'].")";
		mysql_query($sSQL) or die(mysql_error());
		$sSQL = "SELECT header_id, order_no  FROM order_header header where user_id = ".$_SESSION['ID']." and order_status = 0 ";
		$result = mysql_query($sSQL) or die (mysql_error());
		$row = mysql_fetch_assoc($result);
		$header_id = $row['header_id'];	
		echo "新增了一筆訂單, 編號 : " . $row['header_id'];
		
	
	}
	foreach($_POST['selectedItems'] as $key => $value){
		//echo "$key => $value <BR>";
		$sSQL = "UPDATE order_line SET header_id = ".$header_id ." WHERE line_id = ".$value; // 0:未加入訂單申請  1: 已加入訂單申請
		//echo "-".$sSQL ."<BR>";
		mysql_query($sSQL);
	}
	
 ?>
 您現在可以:
 
 <input type= hidden  value = <?php echo $header_id ; ?> name=itemID>
 <input type=submit value=輸入結帳明細>
</form>
或是<a href='home.php'>回到主頁</a>
