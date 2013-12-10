<?php
include("config.php");
include("function.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" /></head>
<body>

<?php
	echo "Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>]<BR>";

	foreach($_POST as $key => $value){
		//echo "$key => $value <BR>";
	}
	if(count($_POST['act_amount_id'])>0){
		foreach ($_POST['act_amount_id'] as $key => $value){
			$sSQL = "UPDATE order_line SET act_amount  = '".$_POST['act_amount'][$key]."' WHERE line_id = ".$value ;
			mysql_query($sSQL) or die(mysql_error());
		}
	
	}
	
	$sSQL = "UPDATE order_header SET
			delivery_mothod = N'".mysql_real_escape_string($_POST['delivery_mothod'])."',
			delivery_time = N'".mysql_real_escape_string($_POST['delivery_time'])."',
			delivery_store_no = N'".mysql_real_escape_string($_POST['sendStoreNo'])."',
			delivery_store_name = N'".mysql_real_escape_string($_POST['delivery_store_name'])."',
			delivery_address = N'".mysql_real_escape_string($_POST['delivery_address'])."',
			delivery_addressee = N'".mysql_real_escape_string($_POST['delivery_addressee'])."',
			user_remark = N'".mysql_real_escape_string($_POST['user_remark'])."',
			admin_remark = N'".mysql_real_escape_string($_POST['admin_remark'])."',
			bank_name_1 = N'".mysql_real_escape_string($_POST['bank_name_1'])."',
			bank_name_1 = N'".mysql_real_escape_string($_POST['bank_name_1'])."',
			bank_code_1 = N'".mysql_real_escape_string($_POST['bank_code_1'])."',
			bank_last5_1 = N'".mysql_real_escape_string($_POST['bank_last5_1'])."',
			bank_time_1 = N'".mysql_real_escape_string($_POST['bank_time_1'])."',
			bank_amount_1 = N'".mysql_real_escape_string($_POST['bank_amount_1'])."',
			bank_name_2 = N'".mysql_real_escape_string($_POST['bank_name_2'])."',
			bank_code_2 = N'".mysql_real_escape_string($_POST['bank_code_2'])."',
			bank_last5_2 = N'".mysql_real_escape_string($_POST['bank_last5_2'])."',
			bank_time_2 = N'".mysql_real_escape_string($_POST['bank_time_2'])."',
			bank_amount_2 = N'".mysql_real_escape_string($_POST['bank_amount_2'])."' , 
			
			order_status = N'".mysql_real_escape_string($_POST['status'])."',
			
			update_date = NOW()  
			";
	
	if ($_POST['tempSave']){
		//$sSQL =	$sSQL." WHERE 	order_status in (0,4)";
		$sSQL =	$sSQL." WHERE  1=1	";
	}else if ($_POST['to1']){
		$sSQL = $sSQL." , order_status = 1 
						WHERE 	order_status = 0";
	}else if ($_POST['to2']){
		$sSQL = $sSQL." , order_status = 2 
						WHERE 	order_status = 1";
	}else if ($_POST['to3']){
		$sSQL = $sSQL." , order_status = 3 
						WHERE 	order_status = 2";
	}else if ($_POST['to4']){
		$sSQL = $sSQL." , order_status = 4 
						WHERE 	order_status = 3";
	}else if ($_POST['to5']){
		$sSQL = $sSQL." , order_status = 5 
						WHERE 	order_status = 4";
	}else if ($_POST['to6']){
		$sSQL = $sSQL." , order_status = 6 
						WHERE 	order_status = 5";
	}else if ($_POST['to7']){
		$sSQL = $sSQL." , order_status = 7 
						WHERE 	order_status = 6";
	}
	
	$sSQL = $sSQL."		
				
				AND header_id = N'".$_POST['itemID']."' ";
							
	//echo "~~~~".$sSQL.$_POST['action'];
	if ($_POST['itemID']==""){
		echo "頁面錯誤,請回到<a href=home.php>主頁面</a>";
		exit;
	}
	if ($_POST['status']=="0" and ($_POST['bank_name_1']=="" or $_POST['bank_code_1']=="" or $_POST['bank_last5_1']=="" or $_POST['bank_amount_1']=="" or $_POST['delivery_mothod']=="" or $_POST['delivery_addressee']=="")){
		echo "<font color=red>頭款必填欄位未填寫,變更未儲存</font><BR>";
		//echo "TEST~~".$_POST['status']."----".$_POST['bank_name_1']."----".$_POST['bank_code_1']."----".$_POST['bank_last5_1']."----".$_POST['bank_amount_1']."----".$_POST['delivery_mothod']."----".$_POST['delivery_addressee ']."<BR>";
	}else if ($_POST['status']=="4" and ($_POST['bank_name_2']=="" or $_POST['bank_code_2']=="" or $_POST['bank_last5_2']=="" or $_POST['bank_amount_2']=="" or $_POST['delivery_mothod']=="" or $_POST['delivery_addressee']=="")){
		echo "<font color=red>尾款必填欄位未填寫,變更未儲存</font><BR>";
		//echo "TEST~~".$_POST['status']."----".$_POST['bank_name_1']."----".$_POST['bank_code_1']."----".$_POST['bank_last5_1']."----".$_POST['bank_amount_1']."----".$_POST['delivery_mothod']."----".$_POST['delivery_addressee ']."<BR>";
	}else{
		if ($_POST['tempSave'] or $_POST['to1'] or $_POST['to2'] or $_POST['to3'] or $_POST['to4'] or $_POST['to5'] or $_POST['to6'] or $_POST['to7'] ){
			echo "<font color=green>儲存成功</font><BR>";
			//echo "Test Message:<pre>".$sSQL."</pre>";
			mysql_query($sSQL) or die (mysql_error());
		}
	}
	$sSQL = "SELECT * from order_header where  header_id = ".$_POST['itemID'];
	$result = mysql_query($sSQL, $link) or die(mysql_error()); // table 必須至有一筆資料
	//echo $sSQL;
	while($row = mysql_fetch_array($result)) {
 
?>

<form action = '' method= post>
訂單編號<input type=text name=itemID value=<?=$row['header_id']?> readonly size=3>
訂單狀態<input type=text name=status value=<?=$row['order_status']?> <?php if (!isAdmin()){ echo "readonly"; }?> size=3>
團號<input type=text value=<?=$row['batch_id']?> readonly size=3 readonly>
<bR>
1.您訂單[<?php echo $row['header_id'];?>] 包含以下商品<BR>
<table border=1>
 <tr>	<td>商品編號</td>
	<td>最後修改時間</td>
	<td>STP Item No</td>
	<td>產品名稱</td>
	<td>顏色</td>	
	<td>大小</td>	
	<td>特殊規格</td>	
	<td>STP網址</td>
	<td>商品數量</td>
	<td>報價</td>
	<td>報價有效期限</td>
<?php	if ($row['order_status']==0){
	echo "<td>從訂單中移除訂單</td>";
}?>
 </tr>
 <?php
	$items_result = mysql_query("SELECT * FROM order_line where header_id = N'".$row['header_id']."'  and `quot_due_date` < date(now()) ", $link); // table 必須至少有一筆資料
	$est_amount=0;
	while($items_r = mysql_fetch_array($items_result)) {
		echo "<tr>";
		echo "<td>".$items_r["line_id"]."</td>";
		echo "<td>".$items_r["update_date"]."</td>";
		echo "<td>".$items_r["stp_item_no"]."</td>";
		echo "<td>".$items_r["stp_item_name"]."</td>";
		echo "<td>".$items_r["stp_color"]."</td>";
		echo "<td>".$items_r["stp_size"]."</td>";
		echo "<td>".$items_r["stp_spec"]."</td>";
		echo "<td><a href='".$items_r["stp_url"]."' target=blank>開啟</a></td>";
		echo "<td>".$items_r["stp_item_count"]."</td>";
		echo "<td>".$items_r["quot_amount"]."</td>";
		echo "<td>".$items_r["quot_due_date"]."</td>";
		if ($row['order_status']==0)
			echo "<td><a href='removeItemFromOrder.php?line_id=".$items_r["line_id"]."&header_id=".$row['header_id']."' >移除</a></td>";
		echo "</tr>";
		$est_amount = $est_amount + ($items_r['quot_amount']*$items_r['stp_item_count']) ; 
	}
 ?>
 <TR><TD colspan=8>費用總額估計:N*30*140% (關稅約9~15%+刷卡手續費1.5%+運費依分攤2~22%)+100國內運費,最後金額實報實銷</TD><TD> <?=($est_amount*30*1.4+100)?></TD><TD colspan=2><font color=green >新台幣</font></TD></TR>
 </table>

 <BR><BR>
 <?php
	if ($row['order_status']!=0){
		$s0_readonly =" readonly ";
	}
	if ($row['order_status']>2){
		$s2_message ="<font color=green>已確認 </font>";
	}else{
		$s2_message ="<font color=red>確認中</font>";
	}
 ?>
 2.頭款資料: <?=$s2_message?>
<table border=1>
 <tr>
	<td><font color=red>匯款人(必填)</font></td> 
	<td><font color=red>匯款銀行(必填)</font></td>
	<td><font color=red>匯款帳號後五碼(必填)</font></td>
	<td>匯款時間</td>
	<td><font color=red>匯款金額NTD(必填)</font></td>
 </tr>
  <tr>
	<td><input maxlength=10 size=10 name=bank_name_1 value='<?php echo $row['bank_name_1'];?>' <?=$s0_readonly?>></td> 
	<td><input maxlength=10 size=10 name=bank_code_1 value='<?php echo $row['bank_code_1'];?>' <?=$s0_readonly?>></td> 
	<td><input maxlength=10 size=10 name=bank_last5_1 value='<?php echo $row['bank_last5_1'];?>'   <?=$s0_readonly?>></td> 
	<td><input maxlength=20 size=10 name=bank_time_1 value='<?php echo $row['bank_time_1'];?>' <?=$s0_readonly?>></td> 
	<td><input maxlength=20 size=10 name=bank_amount_1 value='<?php echo $row['bank_amount_1'];?>' <?=$s0_readonly?>></td>
 </tr>
 </table>

 <BR><BR>


3.請填取貨資訊<BR>
 <table border=1>
 <tr>
	<td><font color=red>交貨方式(必填)</font></td> 
	<td>預定面交日期(面交才填)</td> 
	<td>(店到店才填&nbsp;<a href='http://emap.pcsc.com.tw/01.htm' target=_blank>查店號</a>)</td>
	<td>(郵寄才填)</td>
	<td><font color=red>收件人資料(必填)</font></td>
 </tr>
 <tr>
	<td><select name=delivery_mothod >
			<option value=<?php echo $row['delivery_mothod'];?>><?php echo $row['delivery_mothod'];?></option>
<?php
	if ($row['order_status']==0){		
?>
			<option value=平日關渡八里21:30>平日關渡八里21:30</option>
			<option value=假日關渡八里9:30>假日關渡八里9:30</option>
			<option value=平日古亭9:00>平日古亭9:00</option>
			<option value=平日古亭19:00>平日古亭19:00</option>
			<option value=平日梅花戲院12:10>平日梅花戲院12:10</option>
			<option value=7-11店到店>7-11店到店</option>
			<option value=郵局>郵局</option>
<?php 
	} 
?>
		</select></td> 
	<td>(如:4/15): 		<input maxlength=20 name=delivery_time  size=10 value='<?php echo $row['delivery_time'];?>' <?=$s0_readonly?>> </td>
	<td>店號必填/店名				<input maxlength=14 size=10  name=delivery_store_name value='<?php echo $row['delivery_store_name'];?>' <?=$s0_readonly?> ></td>
	<td>地址: 					<input name=delivery_address value='<?php echo $row['delivery_address'];?>' <?=$s0_readonly?> ></td>
	<td>手機/姓名/備註: 		<input maxlength=40 name=delivery_addressee value='<?php echo $row['delivery_addressee'];?>' <?=$s0_readonly?> >	</td>
 </tr>
 </table>
 
 <BR><BR>
<?php
// // status = 2 --------------------------------------------------- to 3
if ($row['order_status']>=2){
	if ($row['order_status']!=2){
		$s1_readonly =" readonly ";
	}
?>

4.尾款明細:
<BR>
分攤總覽
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
	$batch_result = mysql_query("SELECT * FROM order_batch WHERE batch_id = N'".$row['batch_id']."' "); // table 必須至少有一筆資料
	while($batch_r = mysql_fetch_array($batch_result)) {
		$batch_bill_usd 	= $batch_r['batch_bill_usd']; 
		$batch_bill_ntd 	= $batch_r['batch_bill_ntd'];
		$currency_usd 		= $batch_bill_ntd / $batch_bill_usd;
		$batch_freight_usd 	= $batch_r['batch_freight_usd'];
		$batch_freight_ntd 	= $batch_freight_usd * $currency_usd ;
		$unitFreight 		= $batch_freight_ntd / $batch_bill_ntd; 
		$batch_customs_ntd	=  $batch_r['batch_customs_ntd'];
		$unitCustom 		= $batch_customs_ntd / $batch_bill_ntd;
		$batch_fee_ntd 		= $batch_r['batch_fee_ntd'];
		$unitFee 		= $batch_fee_ntd / $batch_bill_ntd ;
?>

<tr>
	<td><?=$batch_bill_usd?></td>
	<td><?=$batch_bill_ntd?></td>
	<td><?=$currency_usd?></td>
	<td><?=$batch_freight_usd?></td>
	<td><?=$batch_freight_ntd?></</td>
	<td><?=$unitFreight?></td>
	<td><?=$batch_customs_ntd?></td>
	<td><?=$unitCustom?></td>
	<td><?=$batch_fee_ntd?></td>
	<td><?=$unitFee?></td>
	
</tr>
<?php  } ?>
<tr>	<td colspan =11>團號<?=$batch_r['batch_id']?>~<?=$batch_r['batch_no']?></td>
</table>

你的成本
<table border=1>
<tr>	<td>商品編號</td>
	<td>商品實際美金(S1)</td>
	<td>數量(S2)</td>
	<td>商品台幣(T)=S1*S2*C</td>
	<td>+運費分攤(U)=T*F</td>
	<td>+關稅分攤(V)=T*I</td>
	<td>+手續費分攤(W)=T*K</td>
	<td>小計(X)=T+U+V+W</td>
</tr>
<?php
	$items_result = mysql_query("SELECT * FROM order_line where header_id = N'".$row['header_id']."'  and `quot_due_date` < date(now()) ", $link); // table 必須至少有一筆資料
	$est_amount=0;
	if ($row['order_status']!=2 | !isAdmin()){
		$s2_readonly ="readonly";
	}
	while($items_r = mysql_fetch_array($items_result)) {
		$item_id = $items_r["line_id"];
		$itemUSD = ($items_r["act_amount"]);
		$stp_item_count = ($items_r["stp_item_count"]);
		$itemNTD = $itemUSD * $stp_item_count * $currency_usd ;
		$freightNTD  = $itemNTD * $unitFreight;
		$customNTD  = $itemNTD * $unitCustom;
		$feeNTD  = $itemNTD * $unitFee;
		$subTotalNTD = $itemNTD + $freightNTD + $customNTD + $feeNTD;
		$subTotalNTDSum = $subTotalNTDSum+$subTotalNTD;
		echo "<tr>";
		echo "<td><input type=hidden size=6 name=act_amount_id[] ".$s2_readonly." value=".$item_id." >".$item_id."</td>";
		echo "<td><input type=text size=6 name=act_amount[] ".$s2_readonly." value=".$itemUSD."  ></td>";
		echo "<td>".$stp_item_count."</td>";
		echo "<td>".$itemNTD."</td>";
		echo "<td>".$freightNTD."</td>";
		echo "<td>".$customNTD."</td>";
		echo "<td>".$feeNTD."</td>";
		echo "<td>".$subTotalNTD."</td>";
		echo "</tr>";
		
	}
?>

<tr><td colspan=7>金額總計:</td><td><?=$subTotalNTDSum?></td></tr>
<tr><td colspan=7>+國內運費:</td><td></td></tr>
<tr><td colspan=7>-已付金額:</td><td></td></tr>
<tr><td colspan=7>尾款:</td><td></td></tr>
</table>
 <BR><BR>
<?php

} 
// status = 2 --------------------------------------------------- to 3 // 下訂,填寫訂貨金額
// status = 3 --------------------------------------------------- to 4 // 點貨,通知尾款
// status = 4 --------------------------------------------------- to 5 // 匯款,填寫尾款資訊
if ($row['order_status']>=4){
	if ($row['order_status']!=4){
		$s4_readonly =" readonly ";
	}
?>


 
5.<font color=red>尾款</font>資料: 無論面交,店到店,郵寄,此處都要填寫,以便對帳
<table border=1>
 <tr>
	<td><font color=red>匯款人(必填)</font></td> 
	<td><font color=red>匯款銀行(必填)</font></td>
	<td><font color=red>匯款帳號後五碼(必填)</font></td>
	<td>匯款時間</td>
	<td><font color=red>匯款金額NTD(必填)</font></td>
 </tr>
  <tr>
	<td><input maxlength=10 name=bank_name_2 value='<?php echo $row['bank_name_2'];?>' <?=$s4_readonly?>></td> 
	<td><input maxlength=10 name=bank_code_2 value='<?php echo $row['bank_code_2'];?>' <?=$s4_readonly?>></td> 
	<td><input maxlength=10 name=bank_last5_2 value='<?php echo $row['bank_last5_2'];?>'   <?=$s4_readonly?>></td> 
	<td><input maxlength=20 name=bank_time_2 value='<?php echo $row['bank_time_2'];?>' <?=$s4_readonly?>></td> 
	<td><input maxlength=20 size=10 name=bank_amount_2 value='<?php echo $row['bank_amount_2'];?>' <?=$s4_readonly?>></td>
 </tr>
 </table>
 
<?php
} // status = 4 --------------------------------------------------- to 5 // 匯款,填寫尾款資訊
?>
<table>
 <tr> <td>訂單備註</td><td>主購留言</td> </tr>
 <tr>	<td><textarea name=user_remark  cols=60 rows=5 ><?=$row['user_remark']?></textarea></td>
		<td><textarea name=admin_remark cols=60 rows=5 <?php echo (isAdmin())?( "" ):("readonly" );?>><?=$row['admin_remark'] ?> </textarea></td>
 </tr>
 </table>
 <?php

// set user downPay ---end---
		
		echo "<font color=green>";
		// set submit button of form --start--
		switch ($row['order_status'])	{
			case 0: ?>
				<input type=submit name=tempSave value=暫存>
				<input type=submit name=to1 value=通知已付頭款>	<?php
				break;
			case 1: // 已付頭款
				if (isAdmin()){?>
					<input type=submit name=tempSave value=暫存>
					<input type=submit name=to2 value=通知已收頭款>	<?php
				}
				echo "已收到您的訂單,待收到頭款後下定";
				break;
			case 2: //已收頭款	
				if (isAdmin()){?>
					<input type=submit name=tempSave value=暫存>
					<input type=submit name=to3 value=通知已代下單>	<?php
				}
				echo "已收頭款,準備下訂中";
				break;
			case 3:  //已代下單	
				if (isAdmin()){?>
					<input type=submit name=tempSave value=暫存>
					<input type=submit name=to4 value=通知已到貨>	<?php
				}
				echo "已下訂,等到貨囉";
				break;
			case 4: // 已到貨 ?>
				<input type=submit name=tempSave value=暫存>
				<input type=submit name=to5 value=通知已付尾款	>	<?php
				echo "已到貨囉,請付尾款~";
				break;
			case 5: // 已付尾款	
				if (isAdmin()){ ?>
					<input type=submit name=tempSave value=暫存>
					<input type=submit name=to6 value=通知已收尾款>	<?php
				}
				echo "尾款確認中,確認後即出貨";
				
				break;
			case 6: // 已收尾款	
				if (isAdmin()){?>
					<input type=submit name=tempSave value=暫存>
					<input type=submit name=to7 value=通知已出貨>	<?php
				}
				echo "已收到尾款並準備出貨";
				break;
			case 7:  // 已出貨
				if (isAdmin()){?>
					<input type=submit name=tempSave value=存檔>   <?php
				}
				echo "感謝您的信任,有機會再交易,請準備收貨!";
				break;	
		echo "</font>";	
		// set submit button of form ---end---
		}
	} // header select /end
?>

<BR>
</font>
</form>
<?php
// if the form has been posted ( often by temp save or status change)   , show the next related record
if ( $_POST['status']){
	$sNextSQL= "select max(header_id) header_id from order_header where header_id <".$_POST['itemID'] ." and order_status = ".$_POST['status'];
	$next_result = mysql_query($sNextSQL); 
	$next = mysql_fetch_assoc($next_result);
	echo "<form action=editOrderInfo.php method=post>";
	echo "Status :" .$_POST['status'] ;
	echo "<input type=text size=3 name=itemID value=".$next['header_id'].">";
	echo "<input type=submit value=上一個>";
	echo "Left :" .mysql_num_rows(mysql_query("select header_id from order_header")) ;
	$next_result = mysql_query("select min(header_id) header_id from order_header where header_id >".$_POST['itemID'] ." and order_status = ".$_POST['status']) or die (mysql_error()); 
	$next = mysql_fetch_assoc($next_result);
	echo "<input type=text size=3 name=itemID value=".$next['header_id'].">";
	echo "<input type=submit value=下一個>";
	echo "Left :" .mysql_num_rows(mysql_query("select header_id from order_header")) ;
	
	echo "</form>";	
}
?>



 <a href='manageOrder.php'>查看其他訂單 </a><BR>
 <a href='home.php'>回到主頁 </a>
 </body>
 </html>