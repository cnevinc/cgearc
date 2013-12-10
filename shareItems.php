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
<form action=shareItemsHandler.php method=post>

 <input type=submit value=評論商品>
<table border=1>
 <tr>
	<td>撰寫評論<BR>商品編號</td>
	<td>STP Item No</td>
	<td>產品名稱</td>
	<td>顏色</td>	
	<td>大小</td>	
	<td>特殊規格</td>	
	<td>STP網址</td>
	<td>商品數量</td>
	<td>報價(USD)</td>
	<td>評價</td>
	
 </tr>
 <?PHP
	
	$result = mysql_query("SELECT * FROM order_line where header_id is not null  and `quot_due_date` < date(now()) and user_id=".$_SESSION['ID']. " ORDER BY line_id ") ; // table 必須至少有一筆資料

	while($row = mysql_fetch_array($result)) {
		
		echo "<tr>";
		echo "<td><input type=hidden name=selectedItems[] value=".$row["line_id"].">".$row["line_id"]."</td>";
		echo "<td>".$row["stp_item_no"]."</td>";
		echo "<td>".$row["stp_item_name"]."</td>";
		echo "<td>".$row["stp_color"]."</td>";
		echo "<td>".$row["stp_size"]."</td>";
		echo "<td>".$row["stp_spec"]."</td>";
		echo "<td><a href='".$row["stp_url"]."' target=blank>開啟</a></td>";
		echo "<td>".$row["stp_item_count"]."</td>";
		echo "<td>".$row["quot_amount"]."</td>";
		echo "<td><textarea name=review[]>".$row["review"]."</textarea></td>";
		echo "</tr>";

		
	}
 ?>
 </table>
 <input type=submit value=評論商品>
 
 <BR>
 </form>
 <a href="home.php">回到主頁</a><br>
 
 