<?php include("../../include/lib.php");
	sql("update deliveries set state = '$state', driver_id = '$sid', taking_at = now() where id = '$id' ");
?>