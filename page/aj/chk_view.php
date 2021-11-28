<?php include("../../include/lib.php");
	$re = sql("select * from {$kind} where user_id = '$sid' and store_id = '$tid'");
	$num = num($re);
	if($num){ echo "no"; }
 ?>