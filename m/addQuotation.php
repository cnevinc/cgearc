<?php
include("config.php");
include("function.php");

isLoggedIn();

?>
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?="Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>" ?>
<BR>
<form action="" method=post>

我要申請<input type=text name=orderCount size=10 > 件商品報價
<input type=submit value="送出"> 
</form>
<?php

//echo "<pre>";
//print_R($_POST);
//echo "</pre>";

// Generate Lines from self.orderCount-----------------------------
if ($_POST['orderCount']<>""){
?>
注意:按上面送出後,下方資料會清空<BR><BR>
填寫欲申請報價之商品,<font color=red>紅色欄位</font>為必填
<form action=addQuotationHandler.php method=post > 
<table border=1>

<tr>
	<td><font color=red>STP Item No</font><BR>'Item #'不要複製</td>
	<td><font color=red>完整產品名稱</font><BR>如White Sierra Trabagon Rain Pants - Waterproof (For Women)</td>
	<td><font color=red>顏色</font><BR>請連編號都複製,如Chestnut(02)</td>	
	<td>大小<BR>請連編號都複製,如XL(01)</td>	
	<td>特殊規格<BR>請連編號都複製,如R(01)</td>	 
	<td><font color=red>STP網址<BR>請勿縮網址</font></td>
	<td><font color=red>商品數量 </font></td>
</tr>

<?php
	if ($_POST['orderCount']<0){
		$_POST['orderCount'] = 0;
	}
	for ($i=1;$i<=$_POST['orderCount'];$i++){
?>
<tr>
	<td><input type=text name=stp_item_no[] size=10 ></td>
	<td><input type=text name=stp_item_name[] size=60 ></td>
	<td><input type=text name=stp_color[] size=20 ></td>
	<td><input type=text name=stp_size[] size=20 ></td>
	<td><input type=text name=stp_spec[] size=20 ></td>
	<td><input type=text name=stp_url[] size=13 ></td>
	<td><input type=text name=stp_item_count[] value=1 size=4  ></td>
</tr>

<?php 
	}
} 

// Generate Lines from allItems.php-----------------------------
// pass Ref by Line ID
 if (count($_POST['refLine_id'])>0){
 ?>
 
 <table border=1>

<tr>
	<td><font color=red>STP Item No</font><BR>'Item #'不要複製</td>
	<td><font color=red>完整產品名稱</font><BR>如White Sierra Trabagon Rain Pants - Waterproof (For Women)</td>
	<td><font color=red>顏色</font><BR>請連編號都複製,如Chestnut(02)</td>	
	<td>大小<BR>請連編號都複製,如XL(01)</td>	
	<td>特殊規格<BR>請連編號都複製,如R(01)</td>	 
	<td><font color=red>STP網址<BR>請勿縮網址</font></td>
	<td><font color=red>商品數量 </font></td>
</tr>
 
<?php
	foreach($_POST['refLine_id'] as $key => $val){
		$line_ids = $line_ids . $val ." , ";
	}
	$sSQL = "SELECT * 
			FROM 	order_line
			WHERE	line_id in ( ".$line_ids." '')
			ORDER BY line_id ";
	//echo $sSQL;
	$result = mysql_query($sSQL) or die (mysql_error()) ; // table 必須至少有一筆資料

	while($row = mysql_fetch_array($result)) {
?>
 <tr>
	<td><input type=text name=stp_item_no[] size=10 value='<?=$row['stp_item_no']?>' ></td>
	<td><input type=text name=stp_item_name[] value='<?=$row['stp_item_name']?>' size=60 ></td>
	<td><input type=text name=stp_color[] value='<?=$row['stp_color']?>' size=20 ></td>
	<td><input type=text name=stp_size[]  value='<?=$row['stp_size']?>' size=20 ></td>
	<td><input type=text name=stp_spec[] value='<?=$row['stp_spec']?>' size=20 ></td>
	<td><input type=text name=stp_url[] value='<?=$row['stp_url']?>' size=13 ></td>
	<td><input type=text name=stp_item_count[] value=1 size=4  ></td>
 </tr>
<?php		
 
	}
}
?>
</table>
<input type=submit value=申請報價 name=submitQuotation>
</form>
<BR><BR>
<a href="addOrder.php">報價列表</a><BR>
<a href="home.php">回到主頁</a><BR>
 
</body>
</html>
