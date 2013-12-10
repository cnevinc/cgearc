<?php
include("config.php");
include("function.php");

if ($_POST['delete'] ){
	$sSQL = "DELETE FROM  sales_header  WHERE  sales_id =".mysql_real_escape_string($_POST['sales_id']);
	mysql_query($sSQL) ;
}elseif ($_POST['sales_id']){
	$sSQL = "UPDATE  sales_header 
			SET  sales_cname =  '".mysql_real_escape_string($_POST['sales_cname'])."',
				sales_amount =  '".mysql_real_escape_string($_POST['sales_amount'])."',
				sales_bids_url =  '".mysql_real_escape_string($_POST['sales_bids_url'])."',
				sales_shipping =  '".mysql_real_escape_string($_POST['sales_shipping'])."',
				sales_delivery_method =  '".mysql_real_escape_string($_POST['sales_delivery_method'])."',
				sales_desc =  '".mysql_real_escape_string($_POST['sales_desc'])."',
				sales_contact =  '".mysql_real_escape_string($_POST['sales_contact'])."',
				update_date = NOW( ) 
			WHERE  sales_id =".mysql_real_escape_string($_POST['sales_id']);
	mysql_query($sSQL) ;
	if (mysql_error()){
		$_SESSION['Message']=mysql_error();
	}else{
		$_SESSION['Message']="<font color=green>拍賣物品[".$_POST['sales_cname']."]更新完成</font>";
	}
	
}


header("Location: addSales.php");

?>
