<?php
include("config.php");
include("function.php");

isLoggedIn();

foreach($_POST['selectedItems'] as $key => $value){
	//echo "$key => $value <BR>";
	
	$sSQL = "UPDATE order_line SET review = N'".$_POST['review'][$key] ."' WHERE line_id = ".$value; 
	//echo "-".$sSQL ."<BR>";
	mysql_query($sSQL);

}
	header("Location: shareItems.php");
?>