<?php
	$f_name = $_FILES['upfile']['name'];

	if($f_name){
		move_uploaded_file($_FILES['upfile']['tmp_name'], "image/review/{$f_name}");
		sql("insert into reviews(title,contents,image,store_id,user_id) values('$title','$con','/image/review/{$f_name}','$tid','$sid')");
	}else{
		sql("insert into reviews(title,contents,store_id,user_id) values('$title','$con','$tid','$sid')");
	}

	alert("등록되었습니다.");
	back();
?>