<?php
include("config.php");
include("function.php");
isLoggedIn();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
<script type="text/javascript" src="./ckeditor/ckeditor.js"></script>
</head>
<body>
<?="Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>" ?>
<BR>
<?=$_SESSION['Message']?>
<?php $_SESSION['Message']="";?>
<table border=1>
<tr>
<td>商品編號</td>
<td>STP Item No</td>
<td>產品名稱</td>
<td>顏色</td>	
<td>大小</td>	
<td>特殊規格</td>	
<td>STP網址</td>
<td>實際美金</td>
<td>管理拍賣商品</td>
<td>已賣/總共</td>
</tr>
<?PHP

$result = mysql_query("SELECT * FROM order_line where act_amount>0 and user_id=".$_SESSION['ID']. " ORDER BY line_id ") ; // table 必須至少有一筆資料

while($row = mysql_fetch_array($result)) {
	
	echo "<tr>";
	echo "<td>".$row["line_id"]."</td>";
	echo "<td>".$row["stp_item_no"]."</td>";
	echo "<td>".$row["stp_item_name"]."</td>";
	echo "<td>".$row["stp_color"]."</td>";
	echo "<td>".$row["stp_size"]."</td>";
	echo "<td>".$row["stp_spec"]."</td>";
	echo "<td><a href='".$row["stp_url"]."' target=blank>開啟</a></td>";
	echo "<td>".$row["act_amount"]."</td>";
	echo "<td>";

	
	$checkInSaleSQL= "SELECT
					sh.sales_id,
					sh.order_line_id,ol.stp_item_count
			FROM 	sales_header  sh
			JOIN 	order_line ol
				ON sh.order_line_id = ol.line_id
			WHERE 	act_amount > 0 
			AND		ol.line_id = ".$row["line_id"];
	$checkInSaleResult = mysql_query($checkInSaleSQL) or die(" 顯示拍賣失敗".mysql_error().$sSQL);
	
	//echo $checkInSaleSQL."<BR>";
	// if 有東西正在賣
	if (mysql_num_rows($checkInSaleResult) > 0  ){
		// 顯示管理拍賣按鈕
		echo "<select onChange=\"location.href='?sales_id='+this.value+'&action=edit'\" > <option>選擇拍賣編號</option>";
		while($checkInSaleRow= mysql_fetch_assoc($checkInSaleResult)){
			echo "<option value=".$checkInSaleRow["sales_id"]." >管理拍賣".$checkInSaleRow["sales_id"]."</o>";
		}
		echo "</select>";
		// 如果還沒到達上限
	}
	if (mysql_num_rows($checkInSaleResult) ==0 or mysql_num_rows($checkInSaleResult) < $row['stp_item_count']){
		// 顯示新增拍賣按鈕
		echo "<input type=button onclick=\"location.href='?line_id=".$row["line_id"]."'\" value=新增拍賣>";
	}
	echo "</td>";
	echo "<td>".mysql_num_rows($checkInSaleResult) ."/". $row['stp_item_count']."</td>" ;
	
	
	echo "</tr>";

	
}
?>
</table>
<br><br>
<?php
// ------------------------新增拍賣部分----------------------------start
if ($_GET['line_id']<>""){
?>
<form action=addSalesHandler.php method=post>
<font color=red>紅色欄位必填</font>
<table border=1>
	<tr><td>購入物品ID</td><td><input type=hidden name=order_line_id value=<?=$_GET['line_id']?>><?=$_GET['line_id']?></td></tr>
	<tr><td><font color=red>賣物名稱</font></td><td><input type=text name=sales_cname> 給您的物品一個明顯的標題,如:北海道雪祭必備 零下25度Columbia OmniHeat雪靴 </td></tr>
	<tr><td><font color=red>預售金額</font></td><td><input type=text name=sales_amount> 您欲出售最低台幣金額 </td></tr>
	<tr><td>拍賣網址</td><td><input type=text name=sales_bids_url> 若您在Y拍或露天有賣場,網址可以貼這邊,勿縮網址</td></tr>
	<tr><td>運費</td><td><input type=text name=sales_shipping> 請註明各種交貨方式的運費</td></tr>
	<tr><td>交貨方式</td><td><input type=text name=sales_delivery_method>您可以接受的交貨方式,如郵寄,面交</td></tr>
	<tr><td><font color=red>聯絡方式</font></td><td><input type=text name=sales_contact> 此處填寫的聯絡方式,非STPGROUPON團員也看得見,建議填寫"PTT站內信XXXXX(ID)"</td></tr>
	<tr><td><font color=red>商品描述</font></td><td>注意! 請勿在此處填寫個人資料,如手機,信箱,帳號,身分證號.個人資料請私下Email或是用拍賣系統傳遞<textarea class="ckeditor" name="sales_desc"></textarea></td></tr>
	<tr><td colspan=2><input type=submit value=物品上架></td></tr>
</table>

</form>
<?php
}
// ------------------------新增拍賣部分----------------------------end
?>
<?php 
// ------------------------編輯拍賣部分----------------------------start
if ($_GET['sales_id']<>"" and $_GET['action']=="edit"){
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
	<tr><td>預售金額</td><td><input type=text  name=sales_amount value=<?=$row['sales_amount']?>></td></tr>
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
}
// ------------------------編輯拍賣部分----------------------------end
?>
<BR>

<a href="home.php">回到主頁</a><br>
<a href="fleaMarket.php">跳蚤市場</a><br>
