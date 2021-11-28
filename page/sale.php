<?php 
	sql("update breads set sale = '$sale' where id = '$bid' ");
	alert("할인율이 적용되었습니다.");
	back();
 ?>