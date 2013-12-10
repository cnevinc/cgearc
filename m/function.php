<?php
// deprecated
function sSecureInput(array $ar){
	foreach($ar as $key => $value){
		if (is_array($value)){
			$ar[$key]=sSecureInput($value);
			
		}else{
			//print_r($value);
			//echo "<hr>";
			$returnArray[$key] = str_replace("=","",$value);
			$returnArray[$key] = str_replace(" or ","",strtolower($value));
			$returnArray[$key] = str_replace(" and ","",strtolower($value));
			$returnArray[$key] = str_replace("select","",strtolower($value));
			$returnArray[$key] = str_replace("drop ","",strtolower($value));
			$returnArray[$key] = str_replace("drop ","",strtolower($value));
			$returnArray[$key] = str_replace("table ","",strtolower($value));
			$returnArray[$key] = str_replace("database ","",strtolower($value));
			//$returnArray[$key] = str_replace("'","",$value);
			$returnArray[$key] = mysql_real_escape_string($value);
			$returnArray[$key] = str_replace("--","",$value);
			//echo "$key ==> $value<BR>";
		}
		
	}
	return $returnArray;
}
// page_level check login
function isLoggedIn($LOGINTYPE="user"){
	
	//Checks if there isn't a login SESSION
	if(!isset($_SESSION['username'])){
		echo "You are not logged in. Please <a href=login.php>login</a> first!" ;
		exit;		
	}else{
		// 只檢查session 裡面有無username
		$username = $_SESSION['username']; 
		
		if ($LOGINTYPE == "admin "){
			$check = mysql_query("SELECT * FROM users WHERE username = '$username' and ID= 1 ")or die(mysql_error());
		}else{
			$check = mysql_query("SELECT * FROM users WHERE username = '$username'")or die(mysql_error());
		}
		
		if (mysql_num_rows($check)==0){
			echo "You are not allowed to read this page<a href=login.php>Login</a>" ;
			exit;
		}else{
			return true;
		}

	}

}

// session level check Login is 

function isAdmin(){
	if ($_SESSION['ID']==1){
		return true;
	}else {
		return false;
	}
}
function echoAll($coll){
	foreach($coll as $key => $val){
		echo  $key ."=>". $val ."<BR>";
	}
}


?>