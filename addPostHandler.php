<?php
include("config.php");
include("function.php");

//print_r($_POST);

$sSQL = "INSERT INTO post_header(
			user_id ,
			post_subject ,
			post_body ,
			post_cat ,
			post_reply_id,
			creation_date,
			update_date 
			)
			VALUES ";


	if ($_POST['post_subject'] == "" or $_POST['post_body'] == "" ){			
		
		$_SESSION['Message'] ="<font color=red>資料不完整,無法新增文章</font>";
		//echo "<H3>".$_SESSION['error_msg']."</h3>";
	}else{
		$sAddSQL = "(".$_SESSION['ID']." , 
					N'".mysql_real_escape_string($_POST['post_subject'])."' ,
					N'".mysql_real_escape_string($_POST['post_body'])."',
					N'".mysql_real_escape_string($_POST['post_cat'])."',
					".$_POST['post_reply_id'].", NOW(),NOW())" ;

					
		$sSQL = $sSQL .$sAddSQL ;
		
	}
///echo $sSQL."<br>".$_SESSION['Message'] ;
mysql_query($sSQL) or die(mysql_error());
if ($_SESSION['Message']){

} elseif (mysql_error()){
	$_SESSION['Message']="<font color=red>文章新增失敗".mysql_error().$sSQL."</font>";
}elseif (mysql_error()==""){
	//$_SESSION['Message']="<font color=green>文章[".$_POST['sales_cname']."]增新成功</font>";
}

if ($_POST['post_reply_id']==0){
	header("Location: listPost.php");

}else{
	header("Location: showPost.php?post_id=".$_POST['post_reply_id']);
}
?>