<div id="cont">
	<div class="wrap">
		<div class="title_area">
			<h4>할인이벤트</h4>
		</div>
		<?php
			$se_val = isset($se_val) ? $se_val : "";
		?>

		<form method="post" id="se_form" action="<?php echo $url; ?>" enctype="multipart/form-data">
			<table>
				<tr>
					<td style="width:70%"><input type="text" class="form-control" name="se_val" value="<?php echo $se_val;?>"></td>
					<td><input type="submit" class="form-control btn btn-primary" value="검색"></td>
				</tr>
			</table>
		</form>

		<div class="br_list mat60">
			<?php
				$sql = ""; 
				if($se_val){
					$sql = " and name like('%{$se_val}%') ";
				}
				$re = sql("select * from breads where sale > 0 {$sql} order by sale desc ");
				while($da = fe($re)){
					$stores = get_fe("stores","id",$da['store_id']);
			?>

			<div data-store="<?php echo $stores['id'];?>" data-bread="<?php echo $da['id'];?>">
				<img src="<?php echo $da['image']; ?>">
				<p><b><?php echo $stores['name'];?></b></p>
				<p><?php echo $da['name']; ?></p>
				<p>원가 : <?php echo $da['price']." 원";?></p>
				<p>
					<?php 
						$sale = floor( $da['price'] * ((100-$da['sale'])/100) );
						echo "할 인 : {$sale} 원 ( {$da['sale']}% Sale )";

					?>
				</p>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<script>
	$(function (){
		$(".br_list > div").on("click",function(){
			st_id = $(this).attr("data-store");
			br_id = $(this).attr("data-bread");
			addr = `/buy/${st_id}/1/${br_id}`;
			location.href=addr;
		})
	})
</script>