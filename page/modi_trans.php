<?php 
	sql("update users set transportation = '$trans' where id = '$sid' ");
	alert("등록되었습니다.");
	back();
 ?>