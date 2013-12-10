<?php
include("config.php");
include("function.php");
//if ($_SESSION['username']=="")
	//$_SESSION['username']="[<a href=login.pp>登入</a>][<a href=addUser.php>註冊</a>]";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
<script type="text/javascript" src="./ckeditor/ckeditor.js"></script>
</head>
<body>
<?="Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>" ?>
<BR>

<table border=1>
<tr bgcolor=ccff99>
<td>拍賣編號</td>
<td>STP Item No</td>
<td>拍賣商品<BR>英文原名</td>
<td>顏色</td>	
<td>大小</td>	
<td>特殊規格</td>	
<td>STP網址</td>
<td>商品淨值</td>
<td>台幣金額</td>
</tr>
<?PHP
$sSQL= "SELECT
					sh.*,
					ol.line_id,
					ol.stp_item_no,
					ol.stp_item_name,
					ol.stp_url,
					ol.stp_color,
					ol.stp_size,
					ol.stp_spec,
					ol.act_amount,
					ol.batch_id					
			FROM 	sales_header  sh
			LEFT JOIN 	order_line ol
				ON 	sh.order_line_id = ol.line_id
			WHERE 	sh.sales_onShelves = 1";
			
$result = mysql_query($sSQL) ; // table 必須至少有一筆資料

while($row = mysql_fetch_array($result)) {
	
	echo "<tr>";
	echo "<td>".$row["sales_id"]."</td>";
	echo "<td>".$row["stp_item_no"]."</td>";
	echo "<td><font size=5><b><a href=?sales_id=".$row["sales_id"]."&action=view>".$row["sales_cname"]."</a></b></font ><BR>".$row["stp_item_name"]."</td>";
	echo "<td>".$row["stp_color"]."</td>";
	echo "<td>".$row["stp_size"]."</td>";
	echo "<td>".$row["stp_spec"]."</td>";
	echo "<td><a href='".$row["stp_url"]."' target=blank>開啟</a></td>";
	if ($_SESSION['username']){
		echo "<td>".$row["act_amount"]."</td>";
	}else{
		echo "<td>Sorry,註冊才看得到</td>";
	}
	
	echo "<td>".$row["sales_amount"]."</td>";
	
	
	echo "</tr>";

	
}
?>
</table>
<br><br>

<?php 
// ------------------------檢視拍賣部分----------------------------start
if ($_GET['sales_id']<>"" and $_GET['action']=="view"){
	//$_POST = sSecureInput($_POST);
	//echo "<pre>";
	//print_R($_POST);
	//echo "</pre>";
	//echo "<HR>";
	
	
	$sSQL= "SELECT
					sh.*,
					ol.line_id,
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
			AND		sh.sales_id = '".$_GET['sales_id']."' ";
			
	$result = mysql_query($sSQL) or die(" 顯示拍賣失敗".mysql_error().$sSQL);

	if ( mysql_num_rows($result) == 0 ){
		echo "無此商品. 請點此<a href=home.php>回到主頁</a>";
		exit;
	}

	while ($row = mysql_fetch_assoc($result)){
 ?>
 
<BR> 
 <table border=1>
 	<tr bgcolor=ccff99><td>賣物名稱</td><td colspan=5><?=$row['sales_cname']?> </td></tr>
	<tr bgcolor=ffff99><td>產品名稱</td><td colspan=5><?=$row['stp_item_name']?></td></tr>
	<tr><td>拍賣編號</td><td><?=$row['sales_id']?></td><td>購入編號</td><td><?=$row['line_id']?></td><td>STP編號</td><td><?=$row['stp_item_no']?></td></tr>
	<tr><td>STP網頁</td><td><a href=<?=$row['stp_url']?> target=_blank>點我</a></td><td>賣家網頁</td><td><a href=<?=$row['sales_bids_url']?> target=_blank>點我</a></td><td>購買團號</td><td><?=$row['batch_id']?></td></tr>
	<tr><td>顏色	</td><td><?=$row['stp_color']?>&nbsp;</td><td>大小	</td><td><?=$row['stp_size']?> &nbsp;</td><td>特殊規格</td><td><?=$row['stp_spec']?></tr>
	<tr><td>售價	</td><td><?=$row['sales_amount']?> </td><td>運費	</td><td><?=$row['sales_shipping']?> </td><td>交貨方式</td><td  colspan=3><?=$row['sales_delivery_method']?></td></tr>
	<tr><td>聯絡方式</td><td colspan=7><?=$row['sales_contact']?> </td></tr>
	<tr><td>商品描述</td><td colspan=7><?=$row['sales_desc']?></td></tr>
</table>
<?php
	}
}
// ------------------------檢視拍賣部分----------------------------end
?>
<BR>

<a href="home.php">回到主頁</a><br>
<a href="addSales.php">我要賣東西</a><br>
