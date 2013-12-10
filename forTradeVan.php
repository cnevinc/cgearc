<?php 
// Connects to your Database 
include("config.php");
include("function.php");
// Check Permission. Admin Only
if (!isAdmin()){
	echo "You are not allowed to see this page. <a href=login.php> Login </a> ";
	exit;
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

<!--CSV kind of output start-->
Quty, Desc,	Unit Price,	Total<BR>
<?php
	$batchid = $_Get['batch_id'];
	
	$items_result = mysql_query("SELECT order_line.*, users.username FROM order_line , users where ID = user_id and header_id is not null and batch_id=".$_GET['batch_id']." and act_amount!=0 order by  ".$_GET['sortby'] ); // table 必須至少有一筆資料
	while($items_r = mysql_fetch_array($items_result)) {
		$item_id = $items_r["line_id"];
		$itemUSD = ($items_r["act_amount"]);
		$count = $count +$items_r["stp_item_count"] ;;
		$subToatal = $itemUSD*$items_r["stp_item_count"] ;
		$total = $total + $subToatal ;
	
		if ($items_r["act_amount"]<>0){
			$icount_total = $icount_total + $items_r["stp_item_count"];
		}
		
		echo "".$items_r["stp_item_count"].",";
		echo "".$items_r["stp_item_name"].$items_r["stp_color"].$items_r["stp_size"].$items_r["stp_spec"].",";
		echo "".$itemUSD.",";
		echo "".$itemUSD*$items_r["stp_item_count"]."<BR>";

	}
?>
<?=$icount_total?>,-,-,<?=$total?><BR>
<!--CSV kind of output  end -->

回到<a href='home.php'>主頁</a>
 </body>
 </html>
