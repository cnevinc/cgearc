<?php
include("config.php");
include("function.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" /></head>
<body>
<?="Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>][<a href=home.php>回到主頁</a>]<BR>" ?>

<?php
	//$_POST = sSecureInput($_POST);
	//echo "<pre>";
	//print_R($_POST);
	//echo "</pre>";
	//echo "<HR>";
	
 
	$sSQL = "INSERT INTO order_line(
				`createion_date` ,
				`user_id` ,
				`stp_item_no` ,
				`stp_item_name` ,
				`stp_color` ,
				`stp_size` ,
				`stp_spec` ,
				`stp_url` ,
				`stp_item_count` ,
				batch_id
				)
				VALUES ";

	for ($i= 0 ; $i < count($_POST['stp_item_no']) ;$i++){
		if ($_POST['stp_item_no'][$i] == "" or $_POST['stp_item_name'][$i] == "" or $_POST['stp_color'][$i] == "" or $_POST['stp_url'][$i] =="" or $_POST['stp_item_count'][$i] == ""){			
			// echo "<h1>some line not valid</h1>";
			$_SESSION['error_msg'] = $_SESSION['error_msg']."<BR>Item No : [".$_POST['stp_item_no'][$i]."]資料不完整,無法報價";
			echo "<H3>".$_SESSION['error_msg']."</h3>";
		}else{
			$batch = mysql_fetch_assoc(mysql_query("select max(batch_id) mb_id from order_batch "));
			//echo "<h1>valid line</h1>";
			$sAddSQL = "( NOW( ) ,  N'".$_SESSION['ID']."', '".$_POST['stp_item_no'][$i]."',  N'".$_POST['stp_item_name'][$i]."',  N'".$_POST['stp_color'][$i]."',  N'".$_POST['stp_size'][$i]."', N'".$_POST['stp_spec'][$i]."',  N'".$_POST['stp_url'][$i]."',  N'".$_POST['stp_item_count'][$i]."' ,".$batch['mb_id']." )," ;
			$sSQL = $sSQL .$sAddSQL ;
			
		}
		
	}
		
	
	$sSQL = substr($sSQL,0,strlen($sSQL)-1) ; //replace the last comma
	// echo "~~".$sSQL ; 
	mysql_query($sSQL) or die(" 報價新增失敗");
					
 ?>
 
<BR> 已完成新增以下報價申請 , 請等候報價通知 <BR>
 <table border=1>
 <tr>
	<td>STP Item No</td>
	<td>產品名稱</td>
	<td>顏色</td>	
	<td>大小</td>	
	<td>特殊規格</td>	 
	<td>STP網址</td>
	<td>商品數量</td>
 </tr>
 
 <?php
 for ($i=0;$i< count($_POST['stp_item_no']) ;$i++){
 ?>
 <tr>
	<td><?php echo $_POST['stp_item_no'][$i] ;?> </td>
	<td><?php echo $_POST['stp_item_name'][$i];?> </td>
	<td><?php echo $_POST['stp_color'][$i];?> </td>
	<td><?php echo $_POST['stp_size'][$i] ;?> </td>
	<td><?php echo $_POST['stp_spec'][$i] ;?> </td>
	<td><?php echo $_POST['stp_url'][$i] ;?> </td>
	<td><?php echo $_POST['stp_item_count'][$i];?> </td>

 </tr>
  <?php 
 } 
 ?>
 </table>
 <BR>
您現在可以: <BR>
 <a href='home.php'>回到主頁</a> <BR>
 <a href='addOrder.php'>報價列表</a><BR>
 <a href='addQuotation.php'>新增更多報價</a> <BR>