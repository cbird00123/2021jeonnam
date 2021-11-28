<?php include("../../include/lib.php");
	
	$tm = $rdate." ".$rtime;
	sql("insert into reservations(store_id,user_id,reservation_at,state) values('$store_id','$sid','$tm','order')");
?>