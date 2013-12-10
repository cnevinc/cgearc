<?php
include("config.php");
include("function.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" /></head>
<body>

<?php
	echo "Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>";
?>
<!--List all Batches start -->
<form method='get'>
<input type=radio name='sortby' value="username" checked>按照收貨人姓名排列<BR>
<input type=radio name='sortby' value="stp_item_no">按照Item No排列<BR>
<input type=radio name='sortby' value="stp_item_name">按照Item Name排列<BR>
<input type=radio name='sortby' value="header_id">按照訂貨順序排列<BR>
團號: <select name='batch_id'>
<?php
$result = mysql_query("select * from order_batch order by batch_no desc") or die(mysql_error());
if ($_GET['batch_id']) {
	?>	<option value=<?=$_GET['batch_id']?>><?=$_GET['batch_id']?></option>
<?php
}
while($row = mysql_fetch_array($result)) {
?>
	<option value=<?=$row['batch_id']?>><?=$row['batch_no']?>(<?=$row['batch_id']?>)</option>
<?php
}
?>	</select>
<input type=submit>
</form>
<!--List all Batches end -->
<table border=1>

<tr>	
	<td>刷卡美金(A)</td>
	<td>刷卡台幣(B)</td>
	<td>換算匯率(C)=B/A</td>
	<td>運費美金(D)</td>
	<td>運費台幣(E)=D*C<BR></td>
	<td>單位運費(F)=D/(A-D)<BR></td>
	<td>關稅台幣(H)</td>
	<td>單位關稅(I)=H/((A-D)*C)</td>
	<td>手續費金額(J)</td>
	<td>單位手續費金額(K)=E*(1/J)</td>
	
</tr>
<?PHP
	$batch_result = mysql_query("SELECT * FROM order_batch WHERE batch_id =  ".$_GET['batch_id']); // table 必須至少有一筆資料
	while($batch_r = mysql_fetch_array($batch_result)) {
		$batch_bill_usd 	= $batch_r['batch_bill_usd']; 
		$batch_bill_ntd 	= $batch_r['batch_bill_ntd'];
		$unitCurrency 		= ceil($batch_bill_ntd / $batch_bill_usd*10000)/10000;
		$batch_freight_usd 	= $batch_r['batch_freight_usd'];
		$batch_freight_ntd 	= ceil($batch_freight_usd * $unitCurrency );
		$unitFreight 		= ceil($batch_freight_usd / ($batch_bill_usd-$batch_freight_usd)*10000)/10000; 
		$batch_customs_ntd	=  $batch_r['batch_customs_ntd'];
		//$unitCustom 		= ceil($batch_customs_ntd / $batch_bill_ntd*10000)/10000;
		$unitCustom 		= ceil($batch_customs_ntd /  (($batch_bill_usd-$batch_freight_usd)* $unitCurrency) *10000)/10000;
		$batch_fee_ntd 		= $batch_r['batch_fee_ntd'];
		$unitFee 		= ceil($batch_fee_ntd / $batch_bill_ntd*100000)/100000 ;
		$batch_id 		= $batch_r['batch_id'];
		$batch_no 		= $batch_r['batch_no'];
		$batch_remark 		= $batch_r['batch_remark'];
		$batch_total 	=	$batch_bill_ntd  + $batch_customs_ntd + $batch_fee_ntd ; 
?>

<tr>
	<td><?=$batch_bill_usd?></td>
	<td><?=$batch_bill_ntd?></td>
	<td><?=$unitCurrency?></td>
	<td><?=$batch_freight_usd?></td>
	<td><?=$batch_freight_ntd?></</td>
	<td><?=$unitFreight*100?>%</td>
	<td><?=$batch_customs_ntd?></td>
	<td><?=$unitCustom*100?>%</td>
	<td><?=$batch_fee_ntd?></td>
	<td><?=$unitFee*100?>%</td>
	
</tr>
<?php  } ?>
<tr>	<td colspan =11>團號:<?=$batch_no?>  <BR>備註:<font color=red><?=$batch_remark?></font><BR>總金額: <?=$batch_total ?></td>
</table>

你的成本
<table border=1>
<tr>
	<td>到貨</td>
	<td>帳號</td>
	<td>商品號碼</td>
	<td>商品編號</td>
	<td>商品實際美金(S1)</td>
	<td>數量(S2)</td>
	<td>商品台幣(T)=S1*S2*C</td>
	<td>+運費分攤(U)=T*F</td>
	<td>+關稅分攤(V)=T*I</td>
	<td>+手續費分攤(W)=T*K</td>
	<td>小計(X)=T+U+V+W</td>
</tr>
<?php
	$SQL = "SELECT 	l.*,
					h.bank_amount_1,
					u.username 
			FROM	order_line 	l, 
					users		u,
					order_header h
			WHERE	u.ID = l.user_id
			AND		l.header_id = h.header_id
			AND		l.act_amount>0 
			AND 	l.batch_id =".$_GET['batch_id']."
			ORDER BY ".$_GET['sortby'];
	
	$items_result = mysql_query($SQL) or die ($SQL); // table 必須至少有一筆資料
	$est_amount=0;
	if ($row['order_status']!=2 | !isAdmin()){
		$s2_readonly ="readonly";
	}
	if (isAdmin()){
		$s2_readonly =" ";
	}
	while($items_r = mysql_fetch_array($items_result)) {
		$item_id = $items_r["line_id"];
		$itemUSD = ($items_r["act_amount"]);
		$stp_item_count = ($items_r["stp_item_count"]);
		$itemNTD = ceil($itemUSD * $unitCurrency * $stp_item_count) ;
		$freightNTD  = ceil($itemNTD * $unitFreight);
		$customNTD  = ceil($itemNTD * $unitCustom);
		$feeNTD  = ceil($itemNTD * $unitFee);
		$subTotalNTD = $itemNTD + $freightNTD + $customNTD + $feeNTD;
		
		$sumItemUSD = $sumItemUSD + $itemUSD*$stp_item_count;
		$sumStp_item_count = $sumStp_item_count + $stp_item_count;
		$sumItemNTD = $sumItemNTD + $itemNTD;
		$sumFreightNTD = $sumFreightNTD + $freightNTD;
		$sumCustomNTD = $sumCustomNTD + $customNTD;
		$sumFeeNTD = $sumFeeNTD + $feeNTD;
		$sumTotalNTD = $sumTotalNTD + $subTotalNTD;
		
		echo "<tr>";
		echo "<td><input type=checkbox name='arrived' value='".$items_r['item_id']."'></td>";
		echo "<td>".$items_r['username']."</td>";
		echo "<td>$item_id</td>";
		echo "<td>".$items_r['stp_item_no']."</td>";
		echo "<td>$itemUSD</td>";
		echo "<td>".$stp_item_count."</td>";
		echo "<td>".$itemNTD."</td>";
		echo "<td>".$freightNTD."</td>";
		echo "<td>".$customNTD."</td>";
		echo "<td>".$feeNTD."</td>";
		echo "<td>".$subTotalNTD."</td>";
		echo "</tr>";
		
	}
?>

<tr><td colspan=4>金額總計:</td><td><?=$sumItemUSD?></td><td><?=$sumStp_item_count?></td><td><?=$sumItemNTD?></td><td><?=$sumFreightNTD?></td><td><?=$sumCustomNTD?></td><td><?=$sumFeeNTD?></td><td><?=$sumTotalNTD?></td></tr>
<tr><td colspan=10>+國內運費:</td><td><?=$sumTotalNTD?></td></tr>
<tr><td colspan=10>-已付金額:</td><td><?=$sumTotalNTD?></td></tr>
<tr><td colspan=10>尾款:</td><td><?=$sumTotalNTD?></td></tr>
<tr><td colspan=10>倒貼</td><td><?=$sumTotalNTD-$batch_total?></td></tr>
</table>
 <BR><BR>
<table border=1>

<tr>	
	<td>刷卡美金(A)</td>
	<td>刷卡台幣(B)</td>
	<td>換算匯率(C)=B/A</td>
	<td>運費美金(D)</td>
	<td>運費台幣(E)=D*C<BR></td>
	<td>單位運費台幣(F)=E*(1/B)<BR></td>
	<td>關稅台幣(H)</td>
	<td>單位關稅台幣(I)=E*(1/H)</td>
	<td>手續費金額(J)</td>
	<td>單位手續費金額(K)=E*(1/J)</td>
	
</tr>
<?PHP
	$batch_result = mysql_query("SELECT * FROM order_batch WHERE batch_id = 9 "); // table 必須至少有一筆資料
	while($batch_r = mysql_fetch_array($batch_result)) {
		$batch_bill_usd 	= $batch_r['batch_bill_usd']; 
		$batch_bill_ntd 	= $batch_r['batch_bill_ntd'];
		$unitCurrency 		= ceil($batch_bill_ntd / $batch_bill_usd*10000)/10000;
		$batch_freight_usd 	= $batch_r['batch_freight_usd'];
		$batch_freight_ntd 	= ceil($batch_freight_usd * $unitCurrency );
		$unitFreight 		= ceil($batch_freight_ntd / $batch_bill_ntd*10000)/10000; 
		$batch_customs_ntd	=  $batch_r['batch_customs_ntd'];
		$unitCustom 		= ceil($batch_customs_ntd / $batch_bill_ntd*10000)/10000;
		$batch_fee_ntd 		= $batch_r['batch_fee_ntd'];
		$unitFee 		= ceil($batch_fee_ntd / $batch_bill_ntd*100000)/100000 ;
		$batch_id 		= $batch_r['batch_id'];
		$batch_no 		= $batch_r['batch_no'];
		$batch_remark 		= $batch_r['batch_remark'];
?>

<tr>
	
	<td><?=$batch_bill_usd?></td>
	<td><?=$batch_bill_ntd?></td>
	<td><?=$unitCurrency?></td>
	<td><?=$batch_freight_usd?></td>
	<td><?=$batch_freight_ntd?></</td>
	<td><?=$unitFreight*100?>%</td>
	<td><?=$batch_customs_ntd?></td>
	<td><?=$unitCustom*100?>%</td>
	<td><?=$batch_fee_ntd?></td>
	<td><?=$unitFee*100?>%</td>
	
</tr>
<?php  } ?>
<tr>	<td colspan =11>團號:<?=$batch_no?>  <BR>備註:<font color=red><?=$batch_remark?></font></td>
</table>

 <a href='manageOrder.php'>查看其他訂單 </a><BR>
 <a href='home.php'>回到主頁 </a>
 </body>
 </html>