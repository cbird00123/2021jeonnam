<?php 
	sql("update users set location_id = '$loca' where id = '$sid' ");
	alert("등록되었습니다.");
	back();
 ?>