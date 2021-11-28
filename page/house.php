<div id="cont">
	<div class="wrap">
		<div class="title_area">
			<h4>대전빵집 - 검색</h4>
		</div>
		<?php 
			$kind = isset($kind) ? $kind : "name"; 
			$se_val = isset($se_val) ? $se_val : "";
		?>
		<form method="post" id="se_form" action="<?php echo $url; ?>" enctype="multipart/form-data">
			<table>
				<tr>
					<td style="width:30%">
						<select class="form-control" name="kind">
							<option value="name" <?php if($kind == "name"){ echo "selected";}?> >빵집이름</option>
							<option value="menu" <?php if($kind == "menu"){ echo "selected";}?>>메뉴</option>
							<option value="loca" <?php if($kind == "loca"){ echo "selected";}?>>지역</option>
						</select>
					</td>
					<td style="width:50%"><input type="text" class="form-control" name="se_val" value="<?php echo $se_val;?>"></td>
					<td><input type="submit" class="form-control btn btn-primary" value="검색"></td>
				</tr>
			</table>
		</form>

		<?php
			$re = sql("select * from stores"); // 전체 빵집 순회...
			$b_arr = [];  // 검색된 빵집을 넣을 배열...
			while($da = fe($re)){

				$chk = false; //검색에 포함되는 조건...

				$users = get_fe("users","id",$da['user_id']);  // 빵집 사장 회원 정보..
				$loca = get_fe("locations","id",$users['location_id']); // 빵집 위치 정보...

				$re2 = sql("select * from deliveries where store_id = '{$da['id']}' "); // 빵집 주문 정보...
				$count = 0; 
				while($da2 = fe($re2)){  // 주문한 빵의 개수 구함...
					$re3 = sql("select sum(cnt) from delivery_items where delivery_id = '{$da2['id']}'");
					$da3 = fe($re3);
					$count += $da3[0];
				}

				$re2 = sql("select sum(score),count(score) from grades where store_id = '{$da['id']}'");
				$da2 = fe($re2);
				// 평점이 없는 경우 값이 나오지 않음..
				// 소수점 한자리까지 표시.. 소수점 두자리 자동으로 반올림 됨..
				$score = $da2[0] == "" ? 0 : number_format($da2[0]/$da2[1],1); 

				$re2 = sql("select * from reviews where store_id = '{$da['id']}'");  // 리뷰개수
				$num = num($re2);

				$area = $loca['borough']." ".$loca['name'];  //지역

				if(!$se_val){ // 검색어가 없으면 모두 다 포함...
					$b_arr[] = [$count,$da['name'],$da['connect'],$score,$num,$da['image'],$area,$da['id']]; // 주문개수 와 리스트에 보여줄 정보 저장...
				}else{
					if($kind == "name"){
						$tmp = substr_count($da['name'],$se_val); // 빵집 이름에 검색어가 포함되는지 체크..
						if($tmp){ $chk = true; }
					}else if($kind == "menu"){
						$re2 = sql("select * from breads where store_id = '{$da['id']}' ");
						while($da2 = fe($re2)){  // 빵메뉴 중에 검색어가 포함되는지 체크.. 한 메뉴라도 포함되면 true..
							$tmp = substr_count($da2['name'],$se_val);
							if($tmp){ $chk = true; }
						}
					}else{
						$tmp = substr_count($loca['borough'],$se_val); //주소에 검색어가 포함되는지 체크..(구, 동)..
						$tmp += substr_count($loca['name'],$se_val);
						if($tmp){ $chk = true; }
					}

					if($tmp){ $b_arr[] = [$count,$da['name'],$da['connect'],$score,$num,$da['image'],$area,$da['id']]; }
				}
			}

			rsort($b_arr); // 빵 판매개수 내림차로 정렬...
		?>

		<div class="title_area mat60">
			<h4>대전빵집 - 빵집베스트5</h4>
		</div>
		<div class="br_list">
			<?php 
				for($i=0; $i<5; $i++){
					if(isset($b_arr[$i])){
					$no = $i+1;
					$no = "No.${no} "; 
			?>
				<div data-id="<?php echo $b_arr[$i][7];?>">
					<img src="<?php echo $b_arr[$i][5];?>">
					<p><b><?php echo $no.$b_arr[$i][1];?></b></p>
					<p><?php echo "평점 : ".$b_arr[$i][3];?></p>
					<p><?php echo "리뷰 : ".$b_arr[$i][4];?></p>
					<p><?php echo "지역 : ".$b_arr[$i][6];?></p>
					<p><?php echo "Tel : ".$b_arr[$i][2];?></p>
				</div>
			<?php } } ?>
		</div>

		<div class="title_area mat60">
			<h4>대전빵집 - 빵집리스트</h4>
		</div>

		<div class="br_list">
		<?php 
			for($i=5; $i<20; $i++){
				if(isset($b_arr[$i])){ 
		?>
			<div data-id="<?php echo $b_arr[$i][7];?>">
				<img src="<?php echo $b_arr[$i][5];?>">
				<p><b><?php echo $b_arr[$i][1];?></b></p>
				<p><?php echo "평점 : ".$b_arr[$i][3];?></p>
				<p><?php echo "리뷰 : ".$b_arr[$i][4];?></p>
				<p><?php echo "지역 : ".$b_arr[$i][6];?></p>
				<p><?php echo "Tel : ".$b_arr[$i][2];?></p>
			</div>
		<?php } } ?>
		</div>
	</div>
</div>

<script>
	$(function(){
		$(document).on("click",".br_list > div",function(){
			no = $(this).attr("data-id");
			location.href="/buy/"+no;
		})
	})
</script>