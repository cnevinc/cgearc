<?php
include("config.php");
include("function.php");
isLoggedIn();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" /></head>
<body>
<?="Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>" ?>

1. 刪除報價後需重新申請報價 <BR>
2. 請勿按上一頁,以免重複送出 <BR>
<form action=addOrderHandler.php method=post>

 <input type=submit value=加入訂單>
<table border=1>
 <tr>
	<td>加入訂單</td>
	<td>STP Item No</td>
	<td>產品名稱</td>
	<td>顏色</td>	
	<td>大小</td>	
	<td>特殊規格</td>	
	<td>STP網址</td>
	<td>商品數量</td>
	<td>報價(USD)</td>
	<td>報價批次</td>
	<td>刪除</td>
	
 </tr>
 <?PHP
	// fd1.1 handle deletion
	if ($_GET['action']=='delete'){
		
		$sSQL = "DELETE from order_line WHERE header_id is null and line_id = N'".$_GET['itemID']."' and user_id = ".$_SESSION['ID'];//.$_SESSION[];header_id ." WHERE line_id = ".$value; // 0:未加入訂單申請  1: 已加入訂單申請
		//echo $sSQL;
		mysql_query($sSQL) or die (mysql_error());
		
		//echo "-".$sSQL ."<BR>";
		//cho "已刪除此報價, <a href='addOrder.php'>回到報價清單</a>";
	}
	
	// fd 2.1 add quotation
	$sSQL = "SELECT * FROM order_line where header_id is null  and `quot_due_date` < date(now()) and user_id=".$_SESSION['ID']." 
				and batch_id in (select max(batch_id) as max_b_id from order_batch )" ;
	$result = mysql_query($sSQL); // table 必須至少有一筆資料
	//echo $result ;
	while($row = mysql_fetch_array($result)) {
	
		echo "<tr>";
		if ($row["line_shortage"] =='Y' ){
			echo "<td>缺貨中</td> ";
		}else if ( $row["quot_amount"]== '0' ){
			echo "<td>尚未報價<input type=checkbox name=selectedItems[] value=".$row["line_id"]."></td>";
		} else {
			echo "<td><input type=checkbox name=selectedItems[] value=".$row["line_id"]."></td>";
		}
		echo "<td>".$row["stp_item_no"]."</td>";
		echo "<td>".$row["stp_item_name"]."</td>";
		echo "<td>".$row["stp_color"]."</td>";
		echo "<td>".$row["stp_size"]."</td>";
		echo "<td>".$row["stp_spec"]."</td>";
		echo "<td><a href='".$row["stp_url"]."' target=blank>開啟</a></td>";
		echo "<td>".$row["stp_item_count"]."</td>";
		echo "<td>".$row["quot_amount"]."</td>";
		
		$batch = mysql_fetch_assoc(mysql_query("select batch_no from order_batch  where batch_id = ".$row["batch_id"]));
		echo "<td>".$batch['batch_no']."</td>";
		
		echo "<td><a href='?action=delete&itemID=".$row["line_id"]."' onclick=confirm('刪除報價後需重新申請報價,確定嗎?')>刪除</a ></td>";
		echo "</tr>";

		
	}
 ?>
 </table>
 <input type=submit value=加入訂單>
 
 <!--<input type=submit value=刪除報價 onclick="confirm('刪除報價後需重新申請報價,確定嗎?')">-->
 <BR>
 </form>
 <a href="addQuotation.php">新增報價</a><br>
 <a href="manageOrder.php">訂單列表</a><BR>
 <a href="home.php">回到主頁</a><br>
 
 若下訂無報價商品,費用主購將會幫您用最適合全體的折扣下訂(有可能是原價)