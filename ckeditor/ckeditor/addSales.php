<?php
include("../config.php");
include("../function.php");
isLoggedIn();

?>
<html>
<head>
	<title>Replace Textareas by Class Name &mdash; CKEditor Sample</title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<script type="text/javascript" src="ckeditor.js"></script>
	<script src="sample.js" type="text/javascript"></script>
	<link href="sample.css" rel="stylesheet" type="text/css" />
	</head>
<body>
<?="Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>" ?>



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
<td>我要賣這個</td>

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
	echo "<td><input type=button onclick=\"location.href='?line_id=".$row["line_id"]."'\" value=我要賣這個></input></td>";

	echo "</tr>";

	
}
?>
</table>
<?php
if ($_GET['line_id']<>""){
?>
<form action=addSalesHandler.php method=post>

<table>
<tr>
	<td></td><td><textarea class="ckeditor" name="editor1"></textarea></td>
</tr>
</table>
<input type=submit value=新增賣物>
</form>
<?php
}
?>

<BR>

<a href="home.php">回到主頁</a><br>

