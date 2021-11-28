<?php 
	sql("insert into replies(review_id,contents) values('$bid','$con')");
	alert("등록되었습니다.");
	back();
 ?>