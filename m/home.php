<?php
include("config.php");
include("function.php");

isLoggedIn();

?>
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
 <body>
 <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?PHP
echo "Hi! ".$_SESSION['username'] ." [<a href=logout.php>登出</a>]<BR>";
	
?>
 <table border=1>
<TR>
	<TD>報價</TD>
	<TD>好物</TD>
	<TD>下訂</TD>
	<TD>查看</TD>
	<TD>跳蚤</TD>
	<TD>修改</TD>
<?php	if ($_SESSION['ID']==1){  ?>	
	<TD>查詢使用者</TD>
	<TD>管理報價</TD>
	<TD>管理出團</TD>
	<TD>報表</TD>
<?php } ?>	
</TR>
<TR>
	<TD height=150> <a href="addQuotation.php">新增報價</a></TD>
	<TD>			<a href="allItems.php">別人買啥</a></TD>
	<TD height=150> <a href="addOrder.php">加入訂單</a></TD>
	<TD><a href="manageOrder.php">管理訂單</a></TD>

	<TD><a href="fleaMarket.php">跳蚤市場</a></TD>
	<TD><a href="modifyUser.php">修改資料</a></TD>
<?php	if ($_SESSION['ID']==1){  ?>
	<TD><a href="listUser.php">使用者列表</a></TD>
	<TD><a href="adminQuotation.php">管理報價</a><BR><a href="updateActualPrice.php">管理實際金額</a><BR><a href="allPayment.php">管理者對帳</a>
		<BR><a href="manageDelivery.php">管理者出貨</a>
	</TD>
	<TD><a href="addBatch.php">新增一團</a><BR><a href="listBatch.php">出團列表</a></TD>
	<TD><a href="report.php">管理者報表</a></TD>
<?php } ?>	

</TR>
</table>


<BR><BR>
<table border=1>
<TR>
	<TD align=center colspan=2>置底公告</TD></TR>
<TR>
	<TD>日期</TD>
	<TD>公告內容</TD></TR>
<TR>
	<TD>2012-9-29</TD>
	<TD>1.只提供郵寄與面交的選項
		2.不提供退貨協助,請到電腦版網頁的跳蚤市場拍賣<BR>
		3.面交限關渡八里自取,或4樣物品以上,平日九點古亭站面交<BR>
		4.主購匯款帳號:<font color=red>國泰世華013,帳號062505062774</font><BR>
		5.物品加入訂單之後,就無法移除,除非您接受原價購買外,請勿在未報價前直接加入訂單
	 </TD></TR>	

</TABLE>
<?= "System Date:".date(DATE_RFC822)?>
 </body>
 </html>
