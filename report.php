<?php 
// Connects to your Database 
include("config.php");
include("function.php");

if (!isAdmin()){
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


<?php
//echoAll($_SESSION);


echo $ERROR_MSG ."<BR>"; 
echo $INFO_MSG ."<BR>"; 
$sSQL = "select * from users WHERE 1=1 ";
$result = mysql_query($sSQL) or die (mysql_error());
echo "一共有[".mysql_num_rows($result)."]個使用者<BR>";


$sSQL = "select batch_id,
				count(distinct user_id) as u_count,
				count(distinct header_id) as h_count,
				count(distinct line_id) as l_count,
				sum(stp_item_count) as i_count ,
				sum(act_amount*stp_item_count) as tt_amt 
				from order_line where act_amount <> 0 group by batch_id ";
$result = mysql_query($sSQL) or die (mysql_error());
echo "一共出了[".mysql_num_rows($result)."]團<BR>";


while ($row = mysql_fetch_array($result)){
?>
團次:<?=$row['batch_id']?><BR>
人數:<?=$row['u_count']?><BR>
訂單:<?=$row['h_count']?><BR>
品項:<?=$row['l_count']?><BR>
數量:<?=$row['i_count']?><BR>
金額:<?=$row['tt_amt']?><BR>
平均單價: <?=round($row['tt_amt']/$row['l_count'])?><BR>
<HR>
<?php
}
?> 
</table>
回到<a href='home.php'>主頁</a>

 </body>
 </html>
