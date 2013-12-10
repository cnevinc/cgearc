<?php 
// Connects to your Database 
include("config.php");
include("function.php");

if (!isAdmin()){
	echo "You are not allowed to see this page. <a href=login.php> Login </a> ";
	
	exit;
}
//This code runs if the form has been submitted
	if(count($_POST['act_amount_id']) <>0){
		foreach ($_POST['act_amount_id'] as $key => $value){
			$sSQL = "UPDATE order_line SET act_amount  = '".$_POST['act_amount'][$key]."' , stp_item_no  = '".$_POST['stp_item_no'][$key]."'  WHERE line_id = ".$value ;
			//echo $sSQL ."<BR>";
			//if ($_POST['act_amount'][$key]>0)
			mysql_query($sSQL) or die(mysql_error());
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
<form action="" method=post>
<table border=1>
<tr>	
	<td>出團姓名</td>
	<td>商品編號</td>
	<td>商品實際美金(S1)</td>
	<td>報價</td>
	<td>數量(S2)</td>
	<td>STP Item No</td>
	<td>產品名稱</td>
	<td>顏色</td>	
	<td>大小</td>	
	<td>特殊規格</td>	
	<td>STP網址</td>
	
</tr>
<?php
	$items_result = mysql_query("SELECT order_line.*, users.username FROM order_line , users where ID = user_id and header_id is not null and batch_id=3 order by  stp_item_name"); // table 必須至少有一筆資料

	while($items_r = mysql_fetch_array($items_result)) {
		$item_id = $items_r["line_id"];
		$itemUSD = ($items_r["act_amount"]);
		
		$subToatal = $itemUSD*$items_r["stp_item_count"] ;
		$total = $total + $subToatal ;
		
		if (($items_r["act_amount"]-$items_r["quot_amount"])>0){
			$bg = " bgcolor=red";
		}
		if ($items_r["act_amount"]<>0){
			$icount_total = $icount_total + $items_r["stp_item_count"];
		}
		echo "<tr".$bg.">";
		echo "<td>".$items_r['username']."</td>";
		echo "<td><input type=hidden  name=act_amount_id[] value=".$item_id." >".$item_id."</td>";
		echo "<td><input type=text  name=act_amount[] value=".$itemUSD."  ></td>";
		echo "<td>".$items_r["quot_amount"]."</td>";
		echo "<td>".$items_r["stp_item_count"]."</td>";
		echo "<td><input type=text name=stp_item_no[] value='".$items_r["stp_item_no"]."'  ></td>";
		echo "<td>".$items_r["stp_item_name"]."</td>";
		echo "<td>".$items_r["stp_color"]."</td>";
		echo "<td>".$items_r["stp_size"]."</td>";
		echo "<td>".$items_r["stp_spec"]."</td>";
		echo "<td><a href='".$items_r["stp_url"]."' target=blank>開啟</a></td>";
		
		echo "</tr>";
		$bg ="";
	}
?>
<tr><td colspan=2></td><td><?=$total?></td><td colspan=7><?=$icount_total?></td></tr>
</table>
<input type=submit>
</form>
回到<a href='home.php'>主頁</a>
 </body>
 </html>
