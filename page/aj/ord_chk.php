<?php include("../../include/lib.php");
	sql("update {$table} set state = '$state' where id = '$id' ");
?>