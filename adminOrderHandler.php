<?php
include("config.php");
include("function.php");
isLoggedIn("admin");

	// 確定request是從上一頁來的
	// 確定此session的user是誰
	
	if ($_POST['selectedItems'] == null){
		echo "請選擇要加入訂單的物品<BR>";
		echo "<a href='addOrder.php'>回到新增商品</a>";
		exit;
	}
	
	
	foreach($_POST['selectedItems'] as $key => $value){
		//echo "$key => $value <BR>";
		$sSQL = "UPDATE order_line SET quot_amount = ".$_POST['quot_amount'][$key] .", update_date = NOW() WHERE line_id = ".$value; // 0:未加入訂單申請  1: 已加入訂單申請
		//echo "-".$sSQL ."<BR>";
		mysql_query($sSQL);
		
		//header("Location: login.php"); 
	}
	header("Location: adminQuotation.php");
	exit;
 ?>
 您現在可以:
 <a href = 'adminQuotation.php'>繼續報價</a> <BR>
或是回到<a href='home.php'>主頁</a>
