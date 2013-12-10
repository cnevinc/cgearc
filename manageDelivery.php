<?php
include("config.php");
include("function.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" /></head>
<body>
<?PHP
echo "Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>";
	
?>
<form  method=post>
團號:<select name='batch_no'>
<?php

$result = mysql_query("select * from order_batch") or die(mysql_error());
if ($_POST['batch_no']) ?>
	<option value=<?=$_POST['batch_no']?>><?=$_POST['batch_no']?></option>
<?php	
while($row = mysql_fetch_array($result)) {
?>
	<option value=<?=$row['batch_no']?>><?=$row['batch_no']?></option>
<?php
}
?>
	</select>
	<input type=submit>
</form>

<form action=editOrderInfo.php method=post>
	<table border=1>
	<tr>
		<td align=center>訂單編號</td>
		<td>選擇訂單</td>
		<td>更新時間</td>
		<td>尾款</td>		
		<td>團號</td>
		<td>訂單進度</td>
		<td>主購備註</td>
	</tr>
<?php 


$sSQL = "SELECT 
			header.*,
			lcount.line_count,
			users.username,
			lcount.est_amount,
			bat.batch_no
		FROM order_header header 
		LEFT JOIN (select 	count(line_id) AS line_count,
							header_id  ,
							sum(quot_amount) AS est_amount
					from order_line line
					where header_id  is not null
					group by header_id) lcount
			ON	header.header_id = lcount.header_id 
		JOIN users
			ON users.id = header. user_id and users.id not in (select user_id from order_face2face where user_id is not null)
		JOIN order_batch bat
			ON bat.batch_id = header.batch_id	
			";
		if ($_POST['batch_no'])
			$sSQL = $sSQL. " AND bat.batch_no = '".$_POST['batch_no']."' ";

	$sSQL = $sSQL . " and order_status=6  ORDER BY header.header_id "; 

$result = mysql_query($sSQL, $link) or die(mysql_error()); // table 必須至少有一筆資料

while($row = mysql_fetch_array($result)) {
	
	
	//$order_status =	($row['order_status']<>8)?"<input type=radio name=itemID value=".$row['header_id'].">" :"-";
	//$order_status =	"<a href=editOrderInfo.php?header_id=".$row['header_id']."> 看明細</a> ";
	$order_status =	"<input type ='button' onclick=\"location.href='editOrderInfo.php?header_id=".$row['header_id']."'\" value=訂單明細></input>";
	
	
	echo "<tr>";
	echo "	<td align=center>".$row['header_id'];
	if (isAdmin()) echo "<BR>".$row['username'];
	echo 							"</td>";
	echo "	<td>".$order_status."</td>";
	echo "	<td>".substr(date($row['update_date']),0,10)."</td>";
	echo "	<td>".$row['balance_due']."</td>";
	echo "	<td>".$row['batch_no']."</td>";
	echo "	<td>".$row['delivery_mothod']."<BR>".$row['delivery_store_name']."<BR>".$row['delivery_address']."<BR>".$row['delivery_addressee']."</td>";
	echo "	<td><textarea readonly>".$row['admin_remark']."</textarea><BR><textarea>".$row['user_remark']."</textarea></td>";
	echo "</tr>";

	
}
?>
	</table>
	<!--input type=submit value=合併訂單 name='Action'-->
	<!--<input type=submit value=訂單明細 name='Action'>-->
</form>

<a href="home.php">回到主頁</a>

<BR><BR><BR><BR>

訂單狀態說明<HR>
<table border=1>
	<tr>
		<td>編號</td><td>步驟名稱</td><td>您的動作</td><td>我的動作</td>	</tr>
	<tr>
		<td>0</td><td>空白</td><td>匯頭款,進入訂單明細填寫頭款與取貨資訊</td><td>-</td></tr>
	<tr>
		<td>1</td><td>已付頭款</td><td></td>										<td>已收到訂單,未確認頭款</td></tr>
	<tr>
		<td>2</td><td>已收頭款</td><td>-</td>										<td>已確認收到頭款</td></tr>
	<tr>
		<td>3</td><td>已代下單</td><td>-</td>										<td>已下單,若物品缺貨會在訂單明細中特別標示</td></tr>
	<tr>
		<td>4</td><td>已到貨</td><td>匯尾款,進入訂單明細填寫尾款資訊</td>		<td>通知您貨物已經到囉,快來看尾款吧</td></tr>
	<tr>
		<td>5</td><td>已付尾款</td><td></td>										<td>尾款確認中</td></tr>
	<tr>
		<td>6</td><td>已收尾款</td><td>-</td>										<td>已收到尾款並準備出貨</td></tr>
	<tr>
		<td>7</td><td>已出貨</td><td>準備收貨</td>								<td>-</td></tr>

</table>
 <BR>
 
<?= "System Date:".date(DATE_RFC822)?>