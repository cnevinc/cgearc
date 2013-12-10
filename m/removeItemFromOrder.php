<?php
include("config.php");
include("function.php");
isLoggedIn();

$sSQL = "UPDATE order_line SET
	header_id = null
	WHERE line_id = ".mysql_real_escape_string($_GET['line_id']);
	
mysql_query($sSQL);
if (mysql_error()){
	echo "系統發生錯誤,請回到<a href=home.php>主頁</a>";
}else{
?>
<form action =editOrderInfo.php method=post>
<input type= hidden  value = <?php echo $_GET['header_id'] ; ?> name=itemID>
 <input type=submit value=輸入結帳明細>
</form>
或是<a href='home.php'>回到主頁</a>


<?php

}
?>
 