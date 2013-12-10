<?php
include("config.php");
include("function.php");
isLoggedIn("admin");
$PAGE_INTVAL = 10;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" /></head>
<body>
<?PHP
echo "Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>";
	
?>
<form action=adminOrderHandler.php method=post>

 <input type=submit value=報價>
<table border=1 width=100%>
 <tr>
	<td>STP Item</td>
	<td>STP 商品內容</td>
	<td>單品報價(USD)</td>

	
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
	$startRow=$_GET['startRow'];
	if (!isset($startRow) or $startRow <0 ){
		$startRow = 0; 
	}
	$sSQL = "SELECT * FROM order_line where quot_amount = 0 ORDER BY line_id  " ;
		
	$result = mysql_query($sSQL ) ; // table 必須至少有一筆資料
	echo "共".mysql_num_rows($result)."筆尚未報價,目前顯示".$startRow." 到 ".($startRow+$PAGE_INTVAL)."筆<BR>";
	// 還沒報價過的鮮都丟出來
	$sSQL = $sSQL ."LIMIT ".$startRow." , ".($startRow+$PAGE_INTVAL);
	//echo $sSQL ;
	$result = mysql_query($sSQL) ; // table 必須至少有一筆資料
	
	
	while($row = mysql_fetch_array($result)) {
	
		echo "<tr>";
		echo "<td><input type=text name=selectedItems[] value=".$row["line_id"]." size=3>";
		echo "<BR>".$row["stp_item_no"];
	
		echo "<BR>".$row["stp_color"];
		echo "<BR>".$row["stp_size"];
		echo "<BR>".$row["stp_spec"]."</td>";
		echo "<td width=90% height=90%><iframe src='".$row["stp_url"]."'width=100% height=600 ></iframe></td>";
		echo "<td><input type=text name=quot_amount[] value=".$row["quot_amount"]." size=3></td>";
		
		echo "</tr>";

		
	}
 ?>
 </table>
 <input type=submit value=報價>
 
 <BR>
 </form>
 <a href="?startRow=0">第一筆</a>&nbsp
 <a href="?startRow=<?=($startRow-$PAGE_INTVAL)?>">上十筆</a>&nbsp
 <a href="?startRow=<?=($startRow+$PAGE_INTVAL)?>">下十筆</a> &nbsp
 <a href="?startRow=<?=(mysql_num_rows($result)-$PAGE_INTVAL)?>">最後一筆</a>&nbsp
 <br>
 <a href="home.php">回到主頁</a><br>

 <BR>
 若下訂無報價商品,費用主購將會幫您用最適合全體的折扣下訂(有可能是原價)