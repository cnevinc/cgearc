<?php
session_start();

$_SESSION['count']= $_SESSION['count']+1;
?>
<html>
<head>
<meta http-equiv="refresh" content=10; URL=s.php">
</head>
<body>
hi
<iframe width=300 height=300 src="http://apps.facebook.com/naughtynice_tw/results/1/1841"></iframe>
<?php

echo $_SESSION['count'] ;
?>
</body>
</html>
