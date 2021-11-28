<?php member_chk();?>
<div id="cont">
	<div class="wrap">
		<?php $stores = get_fe("stores","id",$no); ?>

		<div class="title_area">
			<h4><?php echo $stores['name'];?> - 주문</h4>
			<button class="btn btn-primary" id="buy_btn" data-store="<?php echo $no;?>">주문하기</button>
		</div>

		<div class="br_list">
			<?php 
				$re = sql("select * from breads where store_id = '$no' ");
				while($da = fe($re)){
			?>

			<div>
				<img src="<?php echo $da['image']; ?>">
				<p><b><?php echo $da['name'];?></b></p>
				<p>가격 : <?php echo $da['price']." 원";?></p>
				<p>
					<?php 
						if($da['sale'] == 0){
							echo "&nbsp;";
						}else{
							$sale = floor( $da['price'] * ((100-$da['sale'])/100) );
							echo "할 인 : {$sale} 원 ( {$da['sale']}% Sale )";
						}

						$val = $search == $da['id'] ? 1 : 0;
					?>
				</p>
				<p><input type="number" data-id="<?php echo $da['id'];?>" class="form-control" value="<?php echo $val;?>" style="text-align: center;"></p>
			</div>
			<?php } ?>
		</div>

		<div class="title_area mat60">
			<h4><?php echo $stores['name'];?> - 예약</h4>
		</div>

		<table>
			<tr>
				<td style="width:40%"><input type="date" class="form-control" id="rdate"></td>
				<td style="width:40%"><input type="time" class="form-control" id="rtime"></td>
				<td><button class="btn btn-primary form-control" id="rbtn" data-id="<?php echo $no;?>">예약하기</button></td>
			</tr>
		</table>

		<div class="title_area mat60">
			<h4><?php echo $stores['name'];?> - 리뷰</h4>
		</div>

		<div>
			<table id="re_table">
				<thead>
					<td>작성자이름</td>
					<td>작성일</td>
					<td style="width:20%">제목</td>
					<td style="width:35%">내용</td>
					<td>사진</td>
					<td>공감 개수</td>
					<td>공감 버튼</td>
					<td>답글</td>
				</thead>
				<tbody>
					<?php 
						$page = $page == "" ? 1 : $page;
						$list = 2;

						$start = ($page-1) * $list;
						$end = $start + 1;

						$re = sql("select * from reviews where store_id = '$no' ");
						$total = num($re);
						$total_page = ceil($total/$list);

						$r_arr = array();
						while($da = fe($re)){
							$re2 = sql("select count(id) from likes where review_id = '{$da['id']}'");
							$da2 = fe($re2);
							$count = $da2[0];
							$r_arr[] = [$count,$da['id']];
						}
						rsort($r_arr);
						
						for($i=$start; $i<=$end; $i++){
							if(isset($r_arr[$i])){
								$da = get_fe("reviews","id",$r_arr[$i][1]);
								$user = get_fe("users","id",$da['user_id']);
					?>
					<tr>
						<td><?php echoo($user['name']);?></td>
						<td><?php echoo($da['write_at']);?></td>
						<td><?php echoo($da['title']);?></td>
						<td><?php echoo($da['contents']);?></td>
						<td><?php if($da['image']){ echo "<img src='{$da['image']}' style='width:100px'>"; }?></td>
						<td><?php echoo($r_arr[$i][0]);?></td>
						<td><button class="btn-sm btn-primary like" data-id="<?php echo $da['id'];?>">공 감</button></td>
						<td>
							<?php if($stores['user_id'] == "$sid"){ ?>
								<button class="btn-sm btn-primary reple" data-id="<?php echo $da['id'];?>">답 글</button>
							<?php } ?>
						</td>
					</tr>

					<?php 
							}
						}
					?>

					<tr>
						<td colspan="8">
							<?php 
								for($i=1; $i<=$total_page; $i++){ 
									echo "<a href='/buy/{$no}/{$i}'>{$i}</a>";
									echo "&nbsp;&nbsp;";
								}
							?>
						</td>
					</tr>
				</tbody>
			</table>
			
		</div>


	</div>
</div>

<script>
	$(function(){
		$(".reple").on("click",function(){
			id = $(this).attr("data-id");
			$("#reple_modal").modal("show");
			$(".bid").val(id);
		})

		$(".like").on("click",function(){
			id = $(this).attr("data-id");
			$.post("/page/aj/like.php",{id:id},function(da){
				if(da.trim()){
					alert("이용할 수 없습니다.");
				}else{
					alert("적용되었습니다.");
					location.reload();
				}
			})
		})
				

		$("#rbtn").on("click",function(){
			store_id = $(this).attr("data-id");
			rdate = $("#rdate").val();
			rtime = $("#rtime").val();
			if(!rdate || !rtime){
				alert("날짜와 시간을 입력해주세요.");
			}else{
				$.post("/page/aj/reserve.php",{store_id:store_id,rdate:rdate,rtime:rtime},function(){
					alert("방문예약 되었습니다.");
					location.reload();
				})
			}
		})

		$("#buy_btn").on("click",function(){
			store_id = $(this).attr("data-store");
			bread_arr = [];
			cnt_arr = [];
			$(".br_list > div").each(function(){
				bread = $(this).find("input").attr("data-id");
				cnt = $(this).find("input").val();
				if(cnt > 0){
					bread_arr.push(bread);
					cnt_arr.push(cnt);
				}
			})
			if(bread_arr.length){
				$.post("/page/aj/bread_buy.php",{store_id:store_id,bread_arr:bread_arr,cnt_arr:cnt_arr},function(da){
					alert(da);
					location.reload();
				})
			}else{
				alert("하나 이상의 상품을 구매하셔야 합니다.");
			}
			
		})
	})

	function get_review(p){
		$.post("/page/aj/get_review.php",{p:p},function(da){
			$("#re_table tbody").html(da);
		})
	}
</script>

<div class="modal fade" id="reple_modal" tabindex="-1" role="dialog" aria-labelledby="cc" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cc">답글</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="/reple" enctype="multipart/form-data">
	      <div class="modal-body">
	      	<table>
	      		<tr>
	      			<td>
	      				<textarea class="form-control" name="con"></textarea>
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