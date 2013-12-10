<?php
include("config.php");
include("function.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
<script type="text/javascript" src="./ckeditor/ckeditor.js"></script>
</head>
<body>
<?="Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>][<a href=addSales.php>回到拍賣列表</a>]<BR>" ?>

<?php
	//$_POST = sSecureInput($_POST);
	//echo "<pre>";
	//print_R($_POST);
	//echo "</pre>";
	//echo "<HR>";
	
	
	$sSQL= "SELECT
					sh.*,
					ol.stp_item_no,
					ol.stp_item_name,
					ol.stp_url,
					ol.stp_color,
					ol.stp_size,
					ol.stp_spec,
					ol.act_amount,
					ol.batch_id					
			FROM 	sales_header  sh
			JOIN 	order_line ol
				ON 	sh.order_line_id = ol.line_id
			WHERE 	sh.sales_onShelves =1
			AND		sh.sales_id = '".$_GET['sales_id']."'
			AND		ol.user_id = ".$_SESSION['ID'] ;
			
	$result = mysql_query($sSQL) or die(" 顯示拍賣失敗".mysql_error().$sSQL);

	if ( mysql_num_rows($result) == 0 ){
		echo "無此商品. 請點此<a href=home.php>回到主頁</a>";
		exit;
	}

	while ($row = mysql_fetch_assoc($result)){
 ?>
 
<BR> 
<form action=editSalesHandler.php method=post> 
 <table border=1>
	<tr><td>拍賣編號</td><td><?=$row['sales_id']?></td></tr>
	<tr><td>我要下架</td><td><input type=checkbox value=<?=$row['sales_id']?> name=delete><--確定下架才勾選<input type=submit value=確定下架></td></tr>
	<tr><td>STP Item No</td><td><?=$row['stp_item_no']?></td></tr>
	<tr><td>產品名稱</td><td><?=$row['stp_item_name']?></td></tr>
	<tr><td>產品網頁</td><td><a href=<?=$row['stp_url']?>>點我</a></td></tr>
	<tr><td>顏色	</td><td><?=$row['stp_color']?>&nbsp;</td></tr>
	<tr><td>大小	</td><td><?=$row['stp_size']?> &nbsp;</td></tr>
	<tr><td>特殊規格</td><td><?=$row['stp_spec']?>&nbsp;</td>	</tr>
	<tr><td>購買團號</td><td><?=$row['batch_id']?></td></tr>
	<tr><td>物品ID	</td><td><?=$row['order_line_id']?><input type=hidden  name=sales_id value=<?=$row['sales_id']?>></td></tr>
	<tr><td>賣物名稱</td><td><input type=text  name=sales_cname value=<?=$row['sales_cname']?>></td></tr>
	<tr><td>拍賣網址</td><td><input type=text  name=sales_bids_url value=<?=$row['sales_bids_url']?>></td></tr>
	<tr><td>運費	</td><td><input type=text  name=sales_shipping value=<?=$row['sales_shipping']?>></td></tr>
	<tr><td>交貨方式</td><td><input type=text  name=sales_delivery_method value=<?=$row['sales_delivery_method']?>></td></tr>
	<tr><td>聯絡方式</td><td><input type=text  name=sales_contact value=<?=$row['sales_contact']?>></td></tr>
	<tr><td>商品描述</td><td><textarea class="ckeditor" name="sales_desc"><?=$row['sales_desc']?></textarea></td></tr>
	<tr><td colspan=2><input type=submit value=更新資料></td></tr>
</table>
</form>
<?php
}
?>
 <BR>
您現在可以: <BR>
<a href='home.php'>回到主頁</a> <BR>
<a href='addSales.php'>拍賣列表</a><BR>
