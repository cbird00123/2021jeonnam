<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="/fontawesome/css/all.css">
	<link rel="stylesheet" href="/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="/js/jquery/jquery-ui.css">
	<link rel="stylesheet" href="/css/common.css">

	<script src="/js/jquery.js"></script>
	<script src="/js/jquery/jquery-ui.js"></script>
	<script src="/bootstrap/js/bootstrap.js"></script>
	<script src="/js/script.js"></script>
</head>

<body>
	<header>
		<div class="wrap">
			<div id="logo">
				<span class="cur" onclick="location.href='/'">WEBSKIILS<span>
			</div>
			<div id="top_menu">
			<?php if($sid){  ?>
				<span><?php echo "&lt;{$sname}&gt;(&lt;{$stype}&gt;)"; ?></span>
				<a href='/logout'>로그아웃</a>	
			<?php }else{ ?>
				<span data-toggle="modal" data-target="#log_modal">로그인</span>	
			<?php } ?>	
			</div>
		</div>
	</header>

	<div id="menu_box" class="wrap">
		<div id="menu">
			<ul><a href="/house">대전 빵집</a></ul>
			<ul><a href="#">스탬프</a></ul>
			<ul><a href="/event">할인 이벤트</a></ul>
			<ul><a href="/my">마이페이지</a></ul>
		</div>
	</div>

	<div style="clear: both;"></div>

<div class="modal fade" id="log_modal" tabindex="-1" role="dialog" aria-labelledby="zz" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="zz">로그인</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="/login" enctype="multipart/form-data">
	      <div class="modal-body">
	      	<table>
	      		<tr>
	      			<td>아이디</td>
	      			<td><input type="text" class="form-control" name="id" required="required"></td>
	      		</tr>

	      		<tr>
	      			<td>비밀번호</td>
	      			<td><input type="password" class="form-control" name="pw" required="required"></td>
	      		</tr>
	      	</table>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">취 소</button>
	        <button type="submit" class="btn btn-primary">로그인</button>
	      </div>
	   </form>
    </div>
  </div>
</div>