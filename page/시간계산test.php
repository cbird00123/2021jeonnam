<?php	// $result_arr = []; // 결과를 저장할 배열....
								// $re2 = sql("select * from distances where vertex1 = '$start'");
								
								// while($da2 = fe($re2)){

								// 	$posi = $da2['id'];

								
								// }

								// $loca_arr = [];
								// $posi_arr = [];

								// $loca_arr[] = [$start]; // 순회해야 하는 위치
								// $deep = 0;

								// for($i=0; $i<count($loca_arr); $i++){

								// 	for($j=0; $j<count($loca_arr[$i]); $j++){

								// 		$r = sql("select * from distances where vertex1 = '$loca_arr[$i]'");
								// 		while($d = fe($re)){

								// 			$posi_arr[$i][$j] =   


								// 		}

								// 	}

								// }


								
								// $result_arr = [];
								
								// $id_arr[0] = [$start];

								// for($i=0; $i<count($id_arr[$deep]); $i++){

								// 	$r = sql("select * from distances where vertex1 = '$id_arr[$i]'");
								// 	$k=0;
								// 	$tmp_arr = [];
								// 	while($d = fe($r)){
								// 		$tmp = $id_arr[$deep].",".$d['id'];
								// 		if($d['vertex2'] == $end){
								// 			$result_arr[] = $tmp;
								// 		}else{
								// 			if(!in_array($d['vertex2'],$data_arr)){
								// 				$tmp_arr[] = $d['vertex2'];
								// 				$data_arr[] = $d['vertex2'];
								// 			}
								// 		}
								// 		$k++;
								// 	}
								// }
								
								// while($loca_arr){
								// 	$r = sql("select * from distances where vertex1 = '$loca_arr[0]'");
								// 	while($d = fe($r)){
								// 		if($d['vertex2'] == $end){
								// 			$result_arr[] = $d['id'];
								// 		}else{
								// 			if(!in_array($d['vertex2'],$tmp_arr)){
								// 				$loca_arr[] = $d['vertex2'];
								// 				$tmp_arr[] = $d['vertex2'];
								// 			}
								// 		}
								// 	}
								// 	array_shift($loca_arr);
								// }
								
								// for($i=0; $i<count($result_arr); $i++){
									
								// 	$chk = true;
								// 	$sel = [$result_arr[$i]];
								// 	$deep = 0;
								// 	$tmp_dis = [];

								// 	while($chk){

								// 		$tmp_sel = [];

								// 		for($j=0; $j<count($sel); $j++){
								// 			$r = sql("select * from distances where id = '$sel[$j]'");
								// 			$d = fe($r);

								// 			if($d['vertex1'] == $start){
								// 				$chk = false;
								// 			}else{
								// 				$tmp_sel[] = $d['id'];
								// 			}
								// 		}
								// 		$deep++;
								// 	}
								// }