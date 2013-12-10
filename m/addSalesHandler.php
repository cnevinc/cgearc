<?php
include("config.php");
include("function.php");

//$_POST = sSecureInput($_POST);
//echo "<pre>";
//print_R($_POST);
//echo "</pre>";
//echo "<HR>";
$sSQL= "SELECT
				sh.order_line_id,ol.stp_item_count
		FROM 	sales_header  sh
		JOIN 	order_line ol
			ON sh.order_line_id = ol.line_id
		WHERE sh.sales_onShelves =1
		AND		act_amount > 0 
		AND		ol.line_id = ".$_POST['order_line_id'];
$result = mysql_query($sSQL) or die(" 檢查拍賣失敗".mysql_error().$sSQL);
$row= mysql_fetch_assoc($result);
//echo $sSQL."<BR>";
if ($row['stp_item_count'] <> "" and mysql_num_rows($result) >= $row['stp_item_count']){
	$_SESSION['Message']= "正在銷售的物品數量已經超過您的購買量.". mysql_num_rows($result) ."/". $row['stp_item_count'];
	
}

$sSQL = "INSERT INTO sales_header(
			user_id ,
			order_line_id ,
			sales_cname ,
			sales_amount,
			sales_onShelves ,
			sales_bids_url ,
			sales_shipping ,
			sales_delivery_method ,
			sales_desc ,
			sales_contact ,
			creation_date ,
			update_date 
			)
			VALUES ";


	if ($_POST['order_line_id'] == "" or $_POST['sales_cname'] == "" or $_POST['sales_desc'] == "" or $_POST['sales_contact'] =="" ){			
		// echo "<h1>some line not valid</h1>";
		$_SESSION['Message'] ="<font color=red>資料不完整,無法新增拍賣</font>";
		//echo "<H3>".$_SESSION['error_msg']."</h3>";
	}else{
		$sAddSQL = "(".$_SESSION['ID']." , 
					".mysql_real_escape_string($_POST['order_line_id'])." ,
					N'".mysql_real_escape_string($_POST['sales_cname'])."',
					N'".mysql_real_escape_string($_POST['sales_amount'])."',1, 
					N'".mysql_real_escape_string($_POST['sales_bids_url'])."', 
					N'".mysql_real_escape_string($_POST['sales_shipping'])."' ,
					N'".mysql_real_escape_string($_POST['sales_delivery_method'])."',
					N'".mysql_real_escape_string($_POST['sales_desc'])."',
					N'".mysql_real_escape_string($_POST['sales_contact'])."', NOW(),NOW())" ;
		$sSQL = $sSQL .$sAddSQL ;
		
	}

//$sSQL = substr($sSQL,0,strlen($sSQL)-1) ; //replace the last comma
//echo "~~".$sSQL."<BR>" ; 
mysql_query($sSQL);
if ($_SESSION['Message']){

} elseif (mysql_error()){
	$_SESSION['Message']="<font color=red>拍賣新增失敗".mysql_error().$sSQL."</font>";
}elseif (mysql_error()==""){
	$_SESSION['Message']="<font color=green>拍賣物品[".$_POST['sales_cname']."]增新成功</font>";
}
header("Location: addSales.php");
?>