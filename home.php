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
<div style="margin: 0px auto;">
    購匯款帳號:<font color=red>國泰世華013,帳號062505062774</font>, 有問題請PTT板上用站內信
</div>
 <table border=1>
<TR>
	<TD>申請報價</TD>
	<TD>加入訂單</TD>
	<TD>關於訂單</TD>
	<TD>心得分享</TD>
	<TD>跳蚤市場</TD>
	<TD>個人資訊</TD>
<?php	if ($_SESSION['ID']==1){  ?>	
	<TD>查詢使用者</TD>
	<TD>管理報價</TD>
	<TD>管理出團</TD>
	<TD>報表</TD>
<?php } ?>	
</TR>
<TR>
	<TD height=150> <a href="addQuotation.php">新增報價</a></TD>
	<TD height=150> <a href="addOrder.php">報價列表</a></TD>
	<TD><a href="manageOrder.php">訂單列表</a></TD>
	<TD><a href="shareItems.php">我的心得</a><BR>
		<a href="allItems.php">別人買啥</a><BR>
		<a href="listPost.php" target=_blank>討論區</a></TD>
	<TD><a href="addSales.php">我要賣這個</a><BR>
		<a href="fleaMarket.php">跳蚤市場</a></TD>
	<TD><a href="modifyUser.php">修改資料</a><font color=red>(NEW!)</font></TD>
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
	<TD>公告日期</TD>
	<TD>公告內容</TD></TR>
<TR>
	<TD>2013-2-20</TD>
	<TD>請詳閱<a href="https://sites.google.com/site/aboutcgearc/">團購規則(必讀)</TD></TR>	
<TR>
	<TD>2013-1-1</TD>
	<TD>1.20121216 目前在等報關<BR>2.20121222目前仍在美國代收處<BR>3.<font color=red>新增"餘款增減記錄"功能</font>,請點"修改資料"看餘款紀錄,如果不正確請站內信主購,謝謝!
	 </TD></TR>	
<TR>
	<TD>2012-07-03</TD>
	<TD>由於7-11現在所有包裝都要用紙箱,還要另外收費. 所以現在<font color=red>只提供郵寄的選項</font>. 謝謝!
	 </TD></TR>	
<TR>
	<TD>2012-05-6</TD>
	<TD>因為以後不見得有退貨團了,因此新增了一個功能讓大家把自己曾經買過的,新的但是size不合的東西拿出來賣,交易的部分建議都在拍賣網站或是站內信溝通,<BR>一個是留下紀錄
		一個也是他們已經有完整的交易制度了,這個網頁<a href=fleaMarket.php>跳蚤市場</a>並沒有設權限,因此也可以給沒有帳號的朋友參考<BR>
		上面也有當初的購入金額,但是沒考慮到運費,關稅等(大約20~40%),所以請給賣家一個價格上的空間! 價錢太低,不如直接向STP退貨,這樣大家又少一個便宜的地方了<BR>
		總之,希望可以貨暢其流!減少重複購買才環保! 
	 </TD></TR>
<TR>
	<TD>2012-04-13</TD>
	<TD>1.STPGroupon 0.3.1上線<BR>2.個人資料未填且請盡速填寫,以便未來核對身分
	 </TD></TR>
<TR>
	<TD>2012-04-12</TD>
	<TD>1.所有帳號以PTT帳號為主,非使用PTT帳號作為系統帳號的朋友隨時可能會被刪除喔,煩請重新註冊<BR>
	 </TD></TR>
<TR>
	<TD>2012-04-11</TD>
	<TD>	1.新增個人資訊修改功能<BR>
			2.結帳前請務必填寫"正確的"PTT帳號,以免漏收站內信件造成誤會<BR>3.系統0.3上線</TD></TR>
<TR>
	<TD>2012-04-10</TD>
	<TD>	1.主購匯款帳號:<font color=red>國泰世華013,帳號062505062774</font>, 有問題請PTT板上用站內信<BR>
			2.系統0.2上線,新增功能請看版本清單</TD></TR>
<TR>
	<TD>2012-04-09</TD>
	<TD>物品加入訂單之後,就無法移除,除了接受原價購買外,請勿直接加入訂單 </TD></TR>
</table>


<div class="fb-like" data-href="http://www.facebook.com/stpgroupon" data-send="true" data-width="450" data-show-faces="true"></div>

<?= "System Date:".date(DATE_RFC822)?>
 </body>
 </html>
