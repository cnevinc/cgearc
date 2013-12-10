<?php
include("config.php");
include("function.php");

//This code runs if the form has been submitted
if(count($_POST['act_amount_id']) <>0){
	foreach ($_POST['act_amount_id'] as $key => $value){
		$sSQL = "UPDATE order_line SET act_amount  = '".$_POST['act_amount'][$key]."' WHERE line_id = ".$value ;
		//echo $sSQL ."<BR>";
		//if ($_POST['act_amount'][$key]>0)
		mysql_query($sSQL) or die(mysql_error());
	}
}

			
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" /></head>
<body>
<?="Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>" ?>
我想要找<a href="?clause=Jacket">[外套]</a>&nbsp;<a href="?clause=Shirt">[上衣]</a>&nbsp;<a href="?clause=base">[內層]</a>&nbsp;
<a href="?clause=Pants">[褲]</a>&nbsp;<a href="?clause=Hat">[帽子]</a>&nbsp;
<a href="?clause=Socks">[襪]</a>&nbsp;&nbsp;<a href="?clause=Boots">[靴]</a>&nbsp;<a href="?clause=Shoes">[鞋]</a>&nbsp;
<a href="?clause=Backpack">[包]</a>&nbsp;<a href="?clause=Gore">[Gore-Tex®]</a>&nbsp; <a href="?clause=Shell">[Soft Shell]</a>&nbsp; 
<a href="?clause=Shell">[Soft Shell]</a>&nbsp; <a href="?clause=Hardwear">[Mountain Hardwea]</a>&nbsp; <a href="?clause=Sleeping">[睡袋]</a>&nbsp; 
 
<a href="?clause=">[都看看]</a>&nbsp;  
<a href="?action=watchHot">[最多人買]</a>&nbsp;  
<a href="?action=review">[有人評價]</a>&nbsp;  <font color=red>New!</font>

<form action=addQuotation.php method=post>
好物列表<BR>
 <input type=submit value=申請報價>

<table border=1>
 <tr>
	<td>我也要買</td>
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
	$sSQL = "SELECT order_line.*
			FROM 	order_line
		
			WHERE header_id <>0 
			AND stp_item_name like '%".mysql_real_escape_string($_GET['clause'])."%'
			ORDER BY stp_item_name ";
	
	if ($_GET['action']=="watchHot"){
		$sSQL = "SELECT order_line.* 
				FROM 	order_line
				JOIN (select stp_item_name ,
						count(stp_item_name) as count from order_line where header_id <>0  group by stp_item_name having count(stp_item_name)>3) hot
						ON hot.stp_item_name = order_line.stp_item_name
				WHERE header_id <> 0 
				ORDER BY stp_item_name 
				";
	
	}
	if ($_GET['action']=="review"){
		$sSQL = "SELECT order_line.* 
				FROM 	order_line
				WHERE review !=''
				ORDER BY stp_item_name 
				";
	
	}
	
	//echo $sSQL;
	$result = mysql_query($sSQL) or die (mysql_error()) ; // table 必須至少有一筆資料

	while($row = mysql_fetch_array($result)) {
		$i++;
		echo "<tr>";
		echo "<td>".$i."<input type=checkbox name=refLine_id[] value=".$row["line_id"].">";
		echo "</td>";
		echo "<td>".$row["stp_item_no"]."</td>";
		echo "<td>".$row["stp_item_name"]."</td>";
		echo "<td>".$row["stp_color"]."</td>";
		echo "<td>".$row["stp_size"]."</td>";
		echo "<td>".$row["stp_spec"]."</td>";
		echo "<td><a href='".$row["stp_url"]."' target=blank>開啟</a></td>";
		echo "<td>".$row["stp_item_count"]."</td>";
		echo "<td>".$row["quot_amount"]."</td>";
		echo "<td>".htmlentities($row["review"],ENT_QUOTES,'utf-8')."</td>";
		echo "</tr>";
		
		
	}
 ?>
 </table>

 <input type=submit value=申請報價>
 </form>
 <BR>
 <a href="home.php">回到主頁</a><br>
 
 