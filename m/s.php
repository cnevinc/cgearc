<?php
session_start();

$_SESSION['count']= $_SESSION['count']+1;
?>
<html>
<head>
<meta http-equiv="refresh" content="<? echo  rand(1,20);?>; URL=s.php">
</head>
<body>
<iframe src="v.php"></iframe>
<?php

echo $_SESSION['count'] ;
?>
</body>
</html>
