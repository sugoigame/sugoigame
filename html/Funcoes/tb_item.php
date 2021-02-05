<?php
function tb_item($tipo){
	switch ($tipo) {
		case 0:
			$tb = "tb_item_acessorio";
			break;
		case 1:
			$tb = "tb_item_comida";
			break;
		case 2:
			$tb = "tb_item_mapa";
			break;
		case 3:
			$tb = "tb_item_navio_casco";
			break;
		case 4:
			$tb = "tb_item_navio_leme";
			break;
		case 5:
			$tb = "tb_item_navio_velas";
			break;
		case 6:
			$tb = "tb_item_pose";
			break;
		case 7:
			$tb = "tb_item_remedio";
			break;
		case 8:
			$tb = "tb_akuma_logia";
			break;
		case 9:
			$tb = "tb_akuma_paramecia";
			break;
		case 10:
			$tb = "tb_akuma_zoen";
			break;
	}
	return $tb;
}