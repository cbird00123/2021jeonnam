<?php include("../../include/lib.php");
	$chk = true;
	$re = sql("select * from reviews where id = '$id' ");
	$da = fe($re);

	if($da['user_id'] == $sid){ $chk = false; }

	$re = sql("select * from likes where review_id = '$id' and user_id = '$sid' ");
	$num = num($re);

	if($num > 0){ $chk = false;}

	if($chk){
		sql("insert into likes(user_id,review_id) values('$sid','$id')");
	}else{
		echo "no";
	}
?>