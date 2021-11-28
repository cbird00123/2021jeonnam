<?php member_chk(); ?>
<div id="cont">

<?php if($stype == "normal"){ //회원이 고객이면.. ?>
	<div class="wrap">
		<div class="title_area">
			<h4>마이페이지 - 주문 조회</h4>
		</div>

		<table>
			<thead>
				<tr>
					<td>빵집 이름</td>
					<td>주문 일시</td>
					<td>빵 종류 및 가격, 수량</td>
					<td>라이더 이름</td>
					<td>도착 예정 시간</td>
					<td>리뷰</td>
					<td>평점</td>
					<td>주문 상태</td>
				</tr>	
			</thead>

			<tbody>
			<?php 
				$re = sql("select * from deliveries where orderer_id = '$sid' order by id desc"); 
				while($da = fe($re)){		
			?>
				<tr>
					<td>
						<?php
							$stores = get_fe("stores","id",$da['store_id']);
						 	echoo($stores['name']);
						 ?>		
					</td>
					<td>
						<?php 
							$g_date = get_date($da['order_at']);
							echoo($g_date); 
						?>
					</td>
					<td>
						<?php
							$re2 = sql("select * from delivery_items where delivery_id = '{$da['id']}'");
							$t_arr = [];
							while($items = fe($re2)){
								$bread_name = get_fe("breads","id",$items['bread_id']);
								$t_arr[] = "{$bread_name['name']} ({$items['price']}원) - {$items['cnt']}개";
							}
							$tmp = implode("<br>",$t_arr);
							echoo($tmp);
						?>
					</td>
					<td>
						<?php
							if($da['state'] == "taking" || $da['state'] == "complete") {
								$driver = get_fe("users","id",$da['driver_id']);
								echoo($driver['name']);
							}
						?>
					</td>
					<td>
						<?php
							if($da['state'] == "taking" || $da['state'] == "complete") {
								$start = $driver['location_id']; //리이더 시작 위치..
								$end = $da['store_id']; // 가게 마지막 위치....
								$dis = cal_dis($start,$end); //거리계산 

								$start = $end; // 가게 시작
								$end = $sloca; // 주문자 집..끝
								$dis2 = cal_dis($start,$end); // 거리계산
								$dis += $dis2; // 총거리 계산..

								$speed = $driver['transportation'] == "bike" ? 15 : 50; // 라이더 교통수단...
								$etime = $dis/$speed * 60; // 거리/속도 로 시간 구한후 분으로 환산... 

								$st = strtotime($da['taking_at']) + ($etime*60); // 라이더가 수락한 일시(초로환산) + 배달시간(초로 환산).. 
								$st = date("Y-m-d H:i:s",$st); //다시 날짜형태로...
								$atime = get_date($st);  // 날짜 포맷 변경...
								echo $atime; 	
							}
						?>
					</td>
					<td>
						<?php if($da['state'] == "complete") { ?>
							<button class="btn btn-primary" onclick="chk_view('<?php echo $sid;?>','<?php echo $da['store_id'];?>','reviews')">리 뷰</button>
						<?php } ?>
					</td>
					<td>
						<?php if($da['state'] == "complete") { ?>
							<button class="btn btn-primary" onclick="chk_view('<?php echo $sid;?>','<?php echo $da['store_id'];?>','grades')">평 점</button>
						<?php } ?>
					</td>
					<td>
						<?php
							if($da['state'] == "order"){echo "주문 대기";}
							if($da['state'] == "accept"){echo "상품 준비 중";}
							if($da['state'] == "reject"){echo "주문 거절";}
							if($da['state'] == "taking"){echo "배달 중";}
							if($da['state'] == "complete"){echo "배달 완료";}
						?>
					</td>
				</tr>
			<?php } //while end ?>
			</tbody>
		</table>

		<div class="title_area mat60">
			<h4>마이페이지 - 예약 조회</h4>
		</div>

		<table>
			<thead>
				<tr>
					<td>빵집 이름</td>
					<td>예약 일시</td>
					<td>예약 신청 일시</td>
					<td>상 태</td>
				</tr>
			</thead>

			<tbody>
		<?php
			$re = sql("select * from reservations where user_id = '$sid' order by id desc");
			while($da = fe($re)){
				$stores = get_fe("stores","id",$da['store_id']);
		?>
				<tr>
					<td><?php echoo($stores['name']); ?></td>
					<td><?php echoo(get_date($da['reservation_at'])); ?></td>
					<td><?php echoo(get_date($da['request_at'])); ?></td>
					<td>
						<?php
							if($da['state'] == "order"){$state = "신청";}
							if($da['state'] == "accept"){$state = "승인";}
							if($da['state'] == "reject"){$state = "거절";}
							echoo($state);
						?>
					</td>
				</tr>
		<?php } ?>
			</tbody>
		</table>
	</div>
<?php } // if end ?>

<?php if($stype == "owner"){ //회원이 사장이면.. ?>
	<div class="wrap">
		<div class="title_area">
			<h4>마이페이지 - 주문 조회</h4>
		</div>
		<table>
			<thead>
				<tr>
					<td>주문자 이름</td>
					<td>배달 주소</td>
					<td>라이더 이름</td>
					<td>도착 예정 시간</td>
					<td>빵 종류 및 가격, 수량</td>
					<td>주문 상태</td>
				</tr>	
			</thead>

			<tbody>
			<?php 
				$stores = get_fe("stores","user_id",$sid);
				$re = sql("select * from deliveries where store_id = '{$stores['id']}' order by id desc"); 
				while($da = fe($re)){
			?>
				<tr>
					<td>
						<?php
							$order = get_fe("users","id",$da['orderer_id']);
							echoo($order['name']);
						?>
					</td>
					<td>
						<?php
							$loca = get_fe("locations","id",$order['location_id']);
							echoo($loca['borough']." ".$loca['name']);
						?>
					</td>
					<td>
						<?php
							if($da['state'] == "taking" || $da['state'] == "complete") {
								$driver = get_fe("users","id",$da['driver_id']);
								echoo($driver['name']);
							}
						?>
					</td>

					<td>
						<?php
							if($da['state'] == "taking" || $da['state'] == "complete") {
								$start = $driver['location_id']; //리이더 시작 위치..
								$end = $da['store_id']; // 가게 마지막 위치....
								$dis = cal_dis($start,$end); //거리계산 

								$start = $end; // 가게 시작
								$end = $sloca; // 주문자 집..끝
								$dis2 = cal_dis($start,$end); // 거리계산
								$dis += $dis2; // 총거리 계산..

								$speed = $driver['transportation'] == "bike" ? 15 : 50; // 라이더 교통수단...
								$etime = $dis/$speed * 60; // 거리/속도 로 시간 구한후 분으로 환산... 

								$st = strtotime($da['taking_at']) + ($etime*60); // 라이더가 수락한 일시(초로환산) + 배달시간(초로 환산).. 
								$st = date("Y-m-d H:i:s",$st); //다시 날짜형태로...
								$atime = get_date($st);  // 날짜 포맷 변경...
								echo $atime; 	
							}
						?>
					</td>

					<td>
						<?php
							$re2 = sql("select * from delivery_items where delivery_id = '{$da['id']}'");
							$t_arr = [];
							while($items = fe($re2)){
								$bread_name = get_fe("breads","id",$items['bread_id']);
								$t_arr[] = "{$bread_name['name']} ({$items['price']}원) - {$items['cnt']}개";
							}
							$tmp = implode("<br>",$t_arr);
							echoo($tmp);
						?>
					</td>

					<td>
						<?php
							if($da['state'] == "order"){
						?>
							<button class='btn-sm btn-primary' onclick='ord_chk("deliveries","<?php echo $da['id'];?>","accept")'>수락</button> 
							<button class='btn-sm btn-danger' onclick='ord_chk("deliveries","<?php echo $da['id'];?>","reject")'>거절</button>  
						<?php } ?>

						<?php
							if($da['state'] == "accept"){echo "수락한 주문";}
							if($da['state'] == "reject"){echo "거절한 주문";}
							if($da['state'] == "taking"){echo "배달 중";}
							if($da['state'] == "complete"){echo "배달 완료";}
						?>
					</td>
				</tr>
			<?php } //while end ?>
			</tbody>
		</table>

		<div class="title_area mat60">
			<h4>마이페이지 - 예약 조회</h4>
		</div>

		<table>
			<thead>
				<tr>
					<td>예약자 이름</td>
					<td>예약 일시</td>
					<td>예약 신청 일시</td>
					<td>상 태</td>
				</tr>
			</thead>

			<tbody>
		<?php
			$re = sql("select * from reservations where store_id = '{$stores['id']}'");
			while($da = fe($re)){
		?>
				<tr>
					<td>
						<?php
							$order = get_fe("users","id",$da['user_id']);
							echoo($order['name']);
						?>		
					</td>
					<td><?php echoo(get_date($da['reservation_at'])); ?></td>
					<td><?php echoo(get_date($da['request_at'])); ?></td>
					<td>
						<?php
							if($da['state'] == "order"){
						?>
							<button class='btn-sm btn-primary' onclick='ord_chk("reservations","<?php echo $da['id'];?>","accept")'>수락</button> 
							<button class='btn-sm btn-danger' onclick='ord_chk("reservations","<?php echo $da['id'];?>","reject")'>거절</button>  
						<?php } ?>

						<?php
							if($da['state'] == "accept"){echo "승인한 예약";}
							if($da['state'] == "reject"){echo "거절한 예약";}
						?>
					</td>
				</tr>
		<?php } ?>
			</tbody>
		</table>

		<div class="title_area mat60">
			<h4>마이페이지 - 메뉴 리스트</h4>
		</div>

		<table>
			<thead>
				<tr>
					<td>이름</td>
					<td>가격</td>
					<td>할인율</td>
					<td>할인가</td>
					<td>총 팔린 개수</td>
					<td>할인</td>
				</tr>
			</thead>

			<tbody>
				<?php
					$re = sql("select * from breads where store_id = '{$stores['id']}'");
					while($da=fe($re)){
				?>
				<tr>
					<td><?php echo $da['name'];?></td>
					<td><?php echo $da['price'];?></td>
					<td><?php if($da['sale'] > 0){ echo $da['sale']; }?></td>
					<td><?php if($da['sale'] > 0){ echo floor( $da['price'] * ((100-$da['sale'])/100) ); } ?></td>
					<td>
						<?php
							$b_total = 0;
							// $re2 = sql("select * from deliveries where store_id = '{$stores['id']}' and state='complete' ");
							// 배달이 완료된 것만...

							$re2 = sql("select * from deliveries where store_id = '{$stores['id']}' ");
							// 주문 들어 온 것 전체...(수락전, 취소 모두 포함..)
							
							while($da2 = fe($re2)){
								$re3 = sql("select * from delivery_items where delivery_id = '{$da2['id']}' and bread_id = '{$da['id']}' ");
								while($da3 = fe($re3)){
									$b_total += $da3['cnt'];
								}
							}
							echo $b_total;
						?>
					</td>
					<td><button class="btn-sm btn-primary" onclick="sale('<?php echo $da['id'];?>')">할인</button></td>
				</tr>

				<?php } ?>
			</tbody>
		</table>
	</div>
<?php } // if end ?>

<?php if($stype == "rider"){ //회원이 라이더이면.. ?>
	<div class="wrap">
		<div class="title_area">
			<h4>마이페이지 - 내 정보</h4>
		</div>

		<?php $driver = get_fe("users","id",$sid); ?>

		<form method="post" action="/modi_loca" enctype="multipart/form-data">
			<table>
				<tr>
					<td style="width:30%">내 위치</td>
					<td style="width:50%">
						<select class="form-control" name="loca">
							<?php
								$re = sql("select * from locations");
								while($da = fe($re)){
							?>
								<option value="<?php echo $da['id'];?>" <?php if($da['id'] == $driver['location_id']){ echo "selected"; } ?>><?php echo $da['borough']." ".$da['name'];?></option>
							<?php	} ?>
						</select>
					</td>
					<td><button type="submit" class="btn btn-primary form-control">등 록</button></td>
				</tr>
			</table>
		</form>

		<form method="post" action="/modi_trans" enctype="multipart/form-data">
			<table class="mat30">
				<tr>
					<td style="width:30%">내 교통수단</td>
					<td style="width:50%">
						<select class="form-control" name="trans">
							<option value="bike" <?php if($driver['transportation'] == "bike"){ echo "selected"; } ?> >자전거</option>
							<option value="motorcycle" <?php if($driver['transportation'] == "motorcycle"){ echo "selected";} ?>>오토바이</option>
						</select>
					</td>
					<td><button type="submit" class="btn btn-primary form-control">등 록</button></td>
				</tr>
			</table>
		</form>

		<div class="title_area mat60">
			<h4>마이페이지 - 배달 리스트</h4>
		</div>

		<table>
			<thead>
				<tr>
					<td>빵집 이름</td>
					<td>빵집 주소</td>
					<td>배달 주소</td>
					<td>도착 예정 시간</td>
					<td>빵 종류 및 가격, 수량</td>
					<td>상태</td>
				</tr>	
			</thead>

			<tbody>
			<?php 
				$re = sql("select * from deliveries where driver_id = '$sid' or state = 'accept' order by id desc"); 
				//내가 수락한 배달 또는 사장이 접수한 배달(다른 라이더 접수 전...)
				while($da = fe($re)){		
			?>
				<tr>
					<td>
						<?php
							$stores = get_fe("stores","id",$da['store_id']);
						 	echoo($stores['name']);
						 ?>		
					</td>
					<td>
						<?php 
							$store_user = get_fe("users","id",$stores['user_id']);
							$store_loca = get_fe("locations","id",$store_user['location_id']);
							echoo($store_loca['borough']." ".$store_loca['name']);
						?>
					</td>
					<td>
						<?php
							$order_user = get_fe("users","id",$da['orderer_id']);
							$order_loca = get_fe("locations","id",$order_user['location_id']);
							echoo($order_loca['borough']." ".$order_loca['name']);
						?>
					</td>

					<td>
						<?php
							if($da['state'] == "taking" || $da['state'] == "complete") {
								$start = $driver['location_id']; //리이더 시작 위치..
								$end = $da['store_id']; // 가게 마지막 위치....
								$dis = cal_dis($start,$end); //거리계산 

								$start = $end; // 가게 시작
								$end = $sloca; // 주문자 집..끝
								$dis2 = cal_dis($start,$end); // 거리계산
								$dis += $dis2; // 총거리 계산..

								$speed = $driver['transportation'] == "bike" ? 15 : 50; // 라이더 교통수단...
								$etime = $dis/$speed * 60; // 거리/속도 로 시간 구한후 분으로 환산... 

								$st = strtotime($da['taking_at']) + ($etime*60); // 라이더가 수락한 일시(초로환산) + 배달시간(초로 환산).. 
								$st = date("Y-m-d H:i:s",$st); //다시 날짜형태로...
								$atime = get_date($st);  // 날짜 포맷 변경...
								echo $atime; 	
							}
						?>
					</td>

					<td>
						<?php
							$re2 = sql("select * from delivery_items where delivery_id = '{$da['id']}'");
							$t_arr = [];
							while($items = fe($re2)){
								$bread_name = get_fe("breads","id",$items['bread_id']);
								$t_arr[] = "{$bread_name['name']} ({$items['price']}원) - {$items['cnt']}개";
							}
							$tmp = implode("<br>",$t_arr);
							echoo($tmp);
						?>
					</td>

					<td>
						<?php	if($da['state'] == "accept"){ ?>
							<button class="btn-sm btn-primary" onclick="rider_chk('<?php echo $da['id'];?>','taking')">수락</button>
						<?php } ?>

						<?php	if($da['state'] == "taking"){ ?>
							<button class="btn-sm btn-primary" onclick="rider_chk('<?php echo $da['id'];?>','complete')">완료</button>
						<?php } ?>

						<?php
							if($da['state'] == "complete"){echo "완료한 배달";}
						?>
					</td>
				</tr>
			<?php } //while end ?>
			</tbody>
		</table>
	</div>
<?php } // if end ?>

</div>

<script>
	function rider_chk(id,state){
		$.post("/page/aj/rider_chk.php",{id:id,state:state},function(){
			alert("적용되었습니다.");
			location.reload();
		})
	}

	function chk_view(sid,tid,kind){
		$.post("/page/aj/chk_view.php",{sid:sid,tid:tid,kind:kind},function(da){
			if(da.trim()){
				alert("이미 참여하였습니다.");
			}else{
				modal = `#${kind}_modal`;
				$(modal).modal("show");
				$(".sid").val(sid);
				$(".tid").val(tid);
			}
		})
	}

	function ord_chk(table,id,state){
		$.post("/page/aj/ord_chk.php",{table,id:id,state:state},function(){
			alert("적용되었습니다.");
			location.reload();
		})
	}

	function sale(id){
		$(".bid").val(id);
		$("#sale").val(0);
		$("#sale_modal").modal("show");
	}

	$(function(){
		$("#sale").on("keyup input",function(){
			if(!$.isNumeric($(this).val()) ) { $(this).val(0); }
			if($(this).val() > 99) { $(this).val(99); }
			$(this).val(Number($(this).val()));
		})
	})
</script>

<div class="modal fade" id="sale_modal" tabindex="-1" role="dialog" aria-labelledby="cc" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cc">할인</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="/sale" enctype="multipart/form-data">
	      <div class="modal-body">
	      	<table>
	      		<tr>
	      			<td>할인율(%)</td>
	      			<td>
	      				<input type="number" min="0" max="99" class="form-control" id="sale" name="sale" value="0" required="required">
	      				<input type="hidden" class="bid" name="bid">
	      			</td>
	      		</tr>
	      	</table>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">취 소</button>
	        <button type="submit" class="btn btn-primary">작 성</button>
	      </div>
	   </form>
    </div>
  </div>
</div>

<div class="modal fade" id="reviews_modal" tabindex="-1" role="dialog" aria-labelledby="aa" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="aa">리뷰작성</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="/wr_reviews" enctype="multipart/form-data">
	      <div class="modal-body">
	      	<table>
	      		<tr>
	      			<td>제 목</td>
	      			<td>
	      				<input type="text" class="form-control" name="title" required="required">
	      				<input type="hidden" class="sid" name="sid">
	      				<input type="hidden" class="tid" name="tid">
	      			</td>
	      		</tr>
	      		<tr>
	      			<td>내 용</td>
	      			<td><textarea class="form-control" name="con" required="required"></textarea></td>
	      		</tr>
	      		<tr>
	      			<td>사 진</td>
	      			<td><input type="file" class="form-control" name="upfile"></td>
	      		</tr>
	      	</table>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">취 소</button>
	        <button type="submit" class="btn btn-primary">작 성</button>
	      </div>
	   </form>
    </div>
  </div>
</div>

<div class="modal fade" id="grades_modal" tabindex="-1" role="dialog" aria-labelledby="bb" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bb">평점작성</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="/wr_grades" enctype="multipart/form-data">
	      <div class="modal-body">
	      	<table>
	      		<tr>
	      			<td>평 점</td>
	      			<td>
	      				<select name="score" class="form-control">
	      					<option value="0">0</option>
	      					<option value="1">1</option>
	      					<option value="2">2</option>
	      					<option value="3">3</option>
	      					<option value="4">4</option>
	      					<option value="5">5</option>
	      				</select>
	      				<input type="hidden" class="sid" name="sid">
	      				<input type="hidden" class="tid" name="tid">
	      			</td>
	      		</tr>
	      	</table>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">취 소</button>
	        <button type="submit" class="btn btn-primary">작 성</button>
	      </div>
	   </form>
    </div>
  </div>
</div>