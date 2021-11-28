<?php include("../../include/lib.php");
	sql("insert into deliveries(store_id,orderer_id,state) values('$store_id','$sid','order')");
	$deli = get_fe("deliveries","store_id",$store_id);
	$id = $deli['id'];
	$total_price = 0;
	$total_cnt = 0;

	for($i=0; $i<count($bread_arr); $i++){
		$bre = get_fe("breads","id",$bread_arr[$i]);
		$price = $bre['sale'] == 0 ? $bre['price'] : floor( $bre['price'] * ((100-$bre['sale'])/100) );

		sql("insert into delivery_items(delivery_id,bread_id,price,cnt) values('{$deli['id']}','{$bre['id']}','$price','$cnt_arr[$i]')");
		$pri = $price * $cnt_arr[$i];
		$total_price += $pri;
		$total_cnt += $cnt_arr[$i];
	}

	$total_price = number_format($total_price);
	echo "총 {$total_cnt}개, {$total_price}원이 주문되었습니다.";
?>