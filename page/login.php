<?php 
	$re = sql("select * from users where id = '$id' and pw = '$pw'");
	$num = num($re);
	if(!$num){
		alert("아이디 혹은 비밀번호를 다시 확인해 주세요.");
		// back();
	}else{
		$da = fe($re);
		$_SESSION['id'] = $da['id'];
		$_SESSION['name'] = $da['name'];
		$_SESSION['type'] = $da['type'];
		$_SESSION['loca'] = $da['location_id'];
		$_SESSION['trans'] = $da['transportation'];
		alert("로그인 되었습니다.");
		move("/");
	}
 ?>