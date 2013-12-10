<?php
session_start();

$link = mysql_connect('localhost', '698386_stpadmin', 'Ss1234567');

mysql_select_db("stpgroupon_99k_db", $link);

mysql_query("SET NAMES 'utf8'"); 




class ErrorMessage{
	public static $MSG_TRANS = "trans";
	public static $MSG_LOGIN = "login";
	public static $MSG_VISIT = "visit";
	public static $MSG_ERROR = "error";
	

	
	public static function log2db($log_type,$log_desc){
		
		switch ($log_type){
			case ErrorMessage::$MSG_TRANS:
				$sql = "INSERT INTO user_log (log_type, log_desc, log_date,user_id ) Values ( '".ErrorMessage::$MSG_TRANS."','".$log_desc."',NOW(),'".$_SESSION['ID']."'   )";
				break;
			case ErrorMessage::$MSG_LOGIN:
				$sql = "INSERT INTO user_log (log_type, log_desc, log_date,user_id ) Values ( '".ErrorMessage::$MSG_LOGIN."','".$log_desc."',NOW(),'".$_SESSION['ID']."'   )";
				//echo "".$sql;
				break;
			case ErrorMessage::$MSG_VISIT:
				$sql = "INSERT INTO user_log (log_type, log_desc, log_date,user_id ) Values ( '".ErrorMessage::$MSG_VISIT."','".$log_desc."',NOW(),'".$_SESSION['ID']."'   )";
				break;
			case ErrorMessage::$MSG_ERROR:
				$sql = "INSERT INTO user_log (log_type, log_desc, log_date,user_id ) Values ( '".ErrorMessage::$MSG_ERROR."','".$log_desc."',NOW(),'".$_SESSION['ID']."'   )";
				break;
		}	
		mysql_query($sql) or die (mysql_error()."<BR>".$sql."HI");
	} 




}
?>