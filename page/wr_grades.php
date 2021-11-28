<?php
	sql("insert into grades(user_id,store_id,score) values('$sid','$tid','$score')");

	alert("등록되었습니다.");
	back();
?>