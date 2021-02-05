<?php

define('REALIZACAO_STATUS_INCOMPLETO', 1);
define('REALIZACAO_STATUS_COMPLETO', 2);
define('REALIZACAO_STATUS_RECOMPENSADO', 3);

class Realizacoes {

	private $userDetails;

	private $pve;

	private $skills;

	public function __construct($userDetails, $connection) {
		$this->userDetails = $userDetails;

		$result = $connection->run("SELECT * FROM tb_pve WHERE id=?", "i", $userDetails->tripulacao["id"]);

		$this->pve = array();
		$rdms = DataLoader::load("rdm");
		foreach ($rdms as $rdm) {
			$this->pve[$rdm["id"]] = 0;
		}
		while ($log = $result->fetch_array()) {
			$this->pve[$log["zona"]] = $log["quant"];
		}

		$this->skills = [];
		foreach ($this->userDetails->personagens as $pers) {
			$this->skills[$pers["cod"]] = [];

			$this->count_skills($connection, $pers["cod"], "chute");
			$this->count_skills($connection, $pers["cod"], "santoryuu");
			$this->count_skills($connection, $pers["cod"], "corte");
			$this->count_skills($connection, $pers["cod"], "tiro");
			$this->count_skills($connection, $pers["cod"], "fogo");
			$this->count_skills($connection, $pers["cod"], "agua");
		}
	}

	private function count_skills($connection, $cod, $key) {
		$result = $connection->run("SELECT * FROM tb_personagens_skil WHERE nome LIKE '%$key%' AND cod = ?", "i", $cod);
		$this->skills[$cod][$key] = $result->count();
	}

	private function count_tripulantes_lvl($lvl_min) {
		$cont = 0;
		foreach ($this->userDetails->personagens as $pers) {
			if ($pers["lvl"] >= $lvl_min) {
				$cont++;
			}
		}
		return $cont;
	}

	private function status($current, $max, $pers = NULL) {
		return array(
			"status" => ($current >= $max
				? array("status" => REALIZACAO_STATUS_COMPLETO, "pers" => $pers)
				: array("status" => REALIZACAO_STATUS_INCOMPLETO, "pers" => $pers)),
			"progresso" => array("current" => $current, "max" => $max)
		);
	}

	private function status_bool($cond, $pers = NULL) {
		return array(
			"status" => ($cond
				? array("status" => REALIZACAO_STATUS_COMPLETO, "pers" => $pers)
				: array("status" => REALIZACAO_STATUS_INCOMPLETO, "pers" => $pers)),
			"progresso" => FALSE
		);
	}

	// lvl 5
	public function status_1() {
		return $this->status($this->userDetails->capitao["lvl"], 5);
	}

	public function status_2() {
		return $this->status($this->count_tripulantes_lvl(5), 3);
	}

	public function status_3() {
		return $this->status($this->count_tripulantes_lvl(5), 5);
	}

	public function status_4() {
		return $this->status($this->count_tripulantes_lvl(5), 10);
	}

	public function status_5() {
		return $this->status($this->count_tripulantes_lvl(5), 15);
	}

	public function status_6() {
		return $this->status($this->count_tripulantes_lvl(5), 20);
	}

	// lvl 10
	public function status_7() {
		return $this->status($this->userDetails->capitao["lvl"], 10);
	}

	public function status_8() {
		return $this->status($this->count_tripulantes_lvl(10), 3);
	}

	public function status_9() {
		return $this->status($this->count_tripulantes_lvl(10), 5);
	}

	public function status_10() {
		return $this->status($this->count_tripulantes_lvl(10), 10);
	}

	public function status_11() {
		return $this->status($this->count_tripulantes_lvl(10), 15);
	}

	public function status_12() {
		return $this->status($this->count_tripulantes_lvl(10), 20);
	}

	// lvl 20
	public function status_13() {
		return $this->status($this->userDetails->capitao["lvl"], 20);
	}

	public function status_14() {
		return $this->status($this->count_tripulantes_lvl(20), 3);
	}

	public function status_15() {
		return $this->status($this->count_tripulantes_lvl(20), 5);
	}

	public function status_16() {
		return $this->status($this->count_tripulantes_lvl(20), 10);
	}

	public function status_17() {
		return $this->status($this->count_tripulantes_lvl(20), 15);
	}

	public function status_18() {
		return $this->status($this->count_tripulantes_lvl(20), 20);
	}

	// lvl 30
	public function status_19() {
		return $this->status($this->userDetails->capitao["lvl"], 30);
	}

	public function status_20() {
		return $this->status($this->count_tripulantes_lvl(30), 3);
	}

	public function status_21() {
		return $this->status($this->count_tripulantes_lvl(30), 5);
	}

	public function status_22() {
		return $this->status($this->count_tripulantes_lvl(30), 10);
	}

	public function status_23() {
		return $this->status($this->count_tripulantes_lvl(30), 15);
	}

	public function status_24() {
		return $this->status($this->count_tripulantes_lvl(30), 20);
	}

	// lvl 40
	public function status_25() {
		return $this->status($this->userDetails->capitao["lvl"], 40);
	}

	public function status_26() {
		return $this->status($this->count_tripulantes_lvl(40), 3);
	}

	public function status_27() {
		return $this->status($this->count_tripulantes_lvl(40), 5);
	}

	public function status_28() {
		return $this->status($this->count_tripulantes_lvl(40), 10);
	}

	public function status_29() {
		return $this->status($this->count_tripulantes_lvl(40), 15);
	}

	public function status_30() {
		return $this->status($this->count_tripulantes_lvl(40), 20);
	}

	// lvl 50
	public function status_31() {
		return $this->status($this->userDetails->capitao["lvl"], 50);
	}

	public function status_32() {
		return $this->status($this->count_tripulantes_lvl(50), 3);
	}

	public function status_33() {
		return $this->status($this->count_tripulantes_lvl(50), 5);
	}

	public function status_34() {
		return $this->status($this->count_tripulantes_lvl(50), 10);
	}

	public function status_35() {
		return $this->status($this->count_tripulantes_lvl(50), 15);
	}

	public function status_36() {
		return $this->status($this->count_tripulantes_lvl(50), 20);
	}

	// reputacao
	public function status_37() {
		return $this->status($this->userDetails->tripulacao["reputacao"], 1000);
	}

	public function status_38() {
		return $this->status($this->userDetails->tripulacao["reputacao"], 3000);
	}

	public function status_39() {
		return $this->status($this->userDetails->tripulacao["reputacao"], 6000);
	}

	public function status_40() {
		return $this->status($this->userDetails->tripulacao["reputacao"], 10000);
	}

	public function status_41() {
		return $this->status($this->userDetails->tripulacao["reputacao"], 15000);
	}

	public function status_42() {
		return $this->status($this->userDetails->tripulacao["reputacao"], 25000);
	}

	public function status_43() {
		return $this->status($this->userDetails->tripulacao["reputacao"], 50000);
	}

	// pvp
	public function status_44() {
		return $this->status($this->userDetails->tripulacao["vitorias"], 10);
	}

	public function status_45() {
		return $this->status($this->userDetails->tripulacao["vitorias"], 20);
	}

	public function status_46() {
		return $this->status($this->userDetails->tripulacao["vitorias"], 50);
	}

	public function status_47() {
		return $this->status($this->userDetails->tripulacao["vitorias"], 100);
	}

	public function status_48() {
		return $this->status($this->userDetails->tripulacao["vitorias"], 200);
	}

	public function status_49() {
		return $this->status($this->userDetails->tripulacao["vitorias"], 500);
	}

	public function status_50() {
		return $this->status($this->userDetails->tripulacao["vitorias"], 700);
	}

	public function status_51() {
		return $this->status($this->userDetails->tripulacao["vitorias"], 1000);
	}

	// golfinhos
	public function status_52() {
		return $this->status($this->pve["1"], 100);
	}

	public function status_53() {
		return $this->status($this->pve["1"], 500);
	}

	public function status_54() {
		return $this->status($this->pve["1"], 1000);
	}

	public function status_55() {
		return $this->status($this->pve["1"], 2000);
	}

	public function status_56() {
		return $this->status($this->pve["1"], 5000);
	}

	public function status_57() {
		return $this->status($this->pve["1"], 10000);
	}

	public function status_58() {
		return $this->status($this->pve["1"], 50000);
	}

	// baleia
	public function status_59() {
		return $this->status($this->pve["2"], 100);
	}

	public function status_60() {
		return $this->status($this->pve["2"], 500);
	}

	public function status_61() {
		return $this->status($this->pve["2"], 1000);
	}

	public function status_62() {
		return $this->status($this->pve["2"], 2000);
	}

	public function status_63() {
		return $this->status($this->pve["2"], 5000);
	}

	public function status_64() {
		return $this->status($this->pve["2"], 10000);
	}

	public function status_65() {
		return $this->status($this->pve["2"], 50000);
	}

	// tubarao
	public function status_66() {
		return $this->status($this->pve["3"], 100);
	}

	public function status_67() {
		return $this->status($this->pve["3"], 500);
	}

	public function status_68() {
		return $this->status($this->pve["3"], 1000);
	}

	public function status_69() {
		return $this->status($this->pve["3"], 2000);
	}

	public function status_70() {
		return $this->status($this->pve["3"], 5000);
	}

	public function status_71() {
		return $this->status($this->pve["3"], 10000);
	}

	public function status_72() {
		return $this->status($this->pve["3"], 50000);
	}

	// filhote rdm
	public function status_73() {
		return $this->status($this->pve["4"], 100);
	}

	public function status_74() {
		return $this->status($this->pve["4"], 500);
	}

	public function status_75() {
		return $this->status($this->pve["4"], 1000);
	}

	public function status_76() {
		return $this->status($this->pve["4"], 2000);
	}

	public function status_77() {
		return $this->status($this->pve["4"], 5000);
	}

	public function status_78() {
		return $this->status($this->pve["4"], 10000);
	}

	public function status_79() {
		return $this->status($this->pve["4"], 50000);
	}

	// rdm
	public function status_80() {
		return $this->status($this->pve["5"], 100);
	}

	public function status_81() {
		return $this->status($this->pve["5"], 500);
	}

	public function status_82() {
		return $this->status($this->pve["5"], 1000);
	}

	public function status_83() {
		return $this->status($this->pve["5"], 2000);
	}

	public function status_84() {
		return $this->status($this->pve["5"], 5000);
	}

	public function status_85() {
		return $this->status($this->pve["5"], 10000);
	}

	public function status_86() {
		return $this->status($this->pve["5"], 50000);
	}

	// rdm gigante
	public function status_87() {
		return $this->status($this->pve["6"], 100);
	}

	public function status_88() {
		return $this->status($this->pve["6"], 500);
	}

	public function status_89() {
		return $this->status($this->pve["6"], 1000);
	}

	public function status_90() {
		return $this->status($this->pve["6"], 2000);
	}

	public function status_91() {
		return $this->status($this->pve["6"], 5000);
	}

	public function status_92() {
		return $this->status($this->pve["6"], 10000);
	}

	public function status_93() {
		return $this->status($this->pve["6"], 50000);
	}

	// filhote dragao
	public function status_94() {
		return $this->status($this->pve["7"], 100);
	}

	public function status_95() {
		return $this->status($this->pve["7"], 500);
	}

	public function status_96() {
		return $this->status($this->pve["7"], 1000);
	}

	public function status_97() {
		return $this->status($this->pve["7"], 2000);
	}

	public function status_98() {
		return $this->status($this->pve["7"], 5000);
	}

	public function status_99() {
		return $this->status($this->pve["7"], 10000);
	}

	public function status_100() {
		return $this->status($this->pve["7"], 50000);
	}

	// dragao
	public function status_101() {
		return $this->status($this->pve["8"], 100);
	}

	public function status_102() {
		return $this->status($this->pve["8"], 500);
	}

	public function status_103() {
		return $this->status($this->pve["8"], 1000);
	}

	public function status_104() {
		return $this->status($this->pve["8"], 2000);
	}

	public function status_105() {
		return $this->status($this->pve["8"], 5000);
	}

	public function status_106() {
		return $this->status($this->pve["8"], 10000);
	}

	public function status_107() {
		return $this->status($this->pve["8"], 50000);
	}

	// recompensa
	public function status_110($pers) {
		return $this->status($pers["fama_ameaca"], 500, $pers["cod"]);
	}

	public function status_111($pers) {
		return $this->status($pers["fama_ameaca"], 1500, $pers["cod"]);
	}

	public function status_112($pers) {
		return $this->status($pers["fama_ameaca"], 2500, $pers["cod"]);
	}

	public function status_113($pers) {
		return $this->status($pers["fama_ameaca"], 4000, $pers["cod"]);
	}

	public function status_114($pers) {
		return $this->status($pers["fama_ameaca"], 5000, $pers["cod"]);
	}

	public function status_115($pers) {
		return $this->status($pers["fama_ameaca"], 10000, $pers["cod"]);
	}

	public function status_116($pers) {
		return $this->status($pers["fama_ameaca"], 15000, $pers["cod"]);
	}

	public function status_117($pers) {
		return $this->status($pers["fama_ameaca"], 20000, $pers["cod"]);
	}

	public function status_118($pers) {
		return $this->status($pers["fama_ameaca"], 25000, $pers["cod"]);
	}

	public function status_119($pers) {
		return $this->status($pers["fama_ameaca"], 30000, $pers["cod"]);
	}

	// exploracao
	public function status_120() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 1);
	}

	public function status_121() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 91
			&& $this->userDetails->tripulacao["res_y"] == 12);
	}

	public function status_122() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 2);
	}

	public function status_123() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 79
			&& $this->userDetails->tripulacao["res_y"] == 20);
	}

	public function status_124() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 3);
	}

	public function status_125() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 55
			&& $this->userDetails->tripulacao["res_y"] == 17);
	}

	public function status_126() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 4);
	}

	public function status_127() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 44
			&& $this->userDetails->tripulacao["res_y"] == 14);
	}

	public function status_128() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 5);
	}

	public function status_129() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 35
			&& $this->userDetails->tripulacao["res_y"] == 27);
	}

	public function status_130() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 6);
	}

	public function status_131() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 16
			&& $this->userDetails->tripulacao["res_y"] == 15);
	}

	public function status_132() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 7);
	}

	public function status_133() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 7
			&& $this->userDetails->tripulacao["res_y"] == 29);
	}

	public function status_134() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 8);
	}

	public function status_135() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 109
			&& $this->userDetails->tripulacao["res_y"] == 7);
	}

	public function status_136() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 9);
	}

	public function status_137() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 120
			&& $this->userDetails->tripulacao["res_y"] == 7);
	}

	public function status_138() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 10);
	}

	public function status_139() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 131
			&& $this->userDetails->tripulacao["res_y"] == 16);
	}

	public function status_140() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 11);
	}

	public function status_141() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 151
			&& $this->userDetails->tripulacao["res_y"] == 22);
	}

	public function status_142() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 12);
	}

	public function status_143() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 168
			&& $this->userDetails->tripulacao["res_y"] == 19);
	}

	public function status_144() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 13);
	}

	public function status_145() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 191
			&& $this->userDetails->tripulacao["res_y"] == 12);
	}

	public function status_146() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 14);
	}

	public function status_147() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 194
			&& $this->userDetails->tripulacao["res_y"] == 31);
	}

	public function status_148() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 15);
	}

	public function status_149() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 87
			&& $this->userDetails->tripulacao["res_y"] == 91);
	}

	public function status_150() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 16);
	}

	public function status_151() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 77
			&& $this->userDetails->tripulacao["res_y"] == 86);
	}

	public function status_152() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 17);
	}

	public function status_153() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 62
			&& $this->userDetails->tripulacao["res_y"] == 83);
	}

	public function status_154() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 18);
	}

	public function status_155() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 50
			&& $this->userDetails->tripulacao["res_y"] == 76);
	}

	public function status_156() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 19);
	}

	public function status_157() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 39
			&& $this->userDetails->tripulacao["res_y"] == 82);
	}

	public function status_158() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 20);
	}

	public function status_159() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 25
			&& $this->userDetails->tripulacao["res_y"] == 78);
	}

	public function status_160() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 21);
	}

	public function status_161() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 7
			&& $this->userDetails->tripulacao["res_y"] == 71);
	}

	public function status_162() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 22);
	}

	public function status_163() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 113
			&& $this->userDetails->tripulacao["res_y"] == 82);
	}

	public function status_164() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 23);
	}

	public function status_165() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 121
			&& $this->userDetails->tripulacao["res_y"] == 77);
	}

	public function status_166() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 24);
	}

	public function status_167() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 131
			&& $this->userDetails->tripulacao["res_y"] == 84);
	}

	public function status_168() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 25);
	}

	public function status_169() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 147
			&& $this->userDetails->tripulacao["res_y"] == 79);
	}

	public function status_170() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 26);
	}

	public function status_171() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 162
			&& $this->userDetails->tripulacao["res_y"] == 85);
	}

	public function status_172() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 27);
	}

	public function status_173() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 183
			&& $this->userDetails->tripulacao["res_y"] == 81);
	}

	public function status_174() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 28);
	}

	public function status_175() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 194
			&& $this->userDetails->tripulacao["res_y"] == 72);
	}

	public function status_176() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 29);
	}

	public function status_177() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 7
			&& $this->userDetails->tripulacao["res_y"] == 49);
	}

	public function status_178() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 30);
	}

	public function status_179() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 17
			&& $this->userDetails->tripulacao["res_y"] == 46);
	}

	public function status_180() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 31);
	}

	public function status_181() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 24
			&& $this->userDetails->tripulacao["res_y"] == 50);
	}

	public function status_182() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 32);
	}

	public function status_183() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 31
			&& $this->userDetails->tripulacao["res_y"] == 54);
	}

	public function status_184() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 33);
	}

	public function status_185() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 37
			&& $this->userDetails->tripulacao["res_y"] == 47);
	}

	public function status_186() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 34);
	}

	public function status_187() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 37
			&& $this->userDetails->tripulacao["res_y"] == 48);
	}

	public function status_188() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 35);
	}

	public function status_189() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 39
			&& $this->userDetails->tripulacao["res_y"] == 47);
	}

	public function status_190() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 36);
	}

	public function status_191() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 39
			&& $this->userDetails->tripulacao["res_y"] == 48);
	}

	public function status_192() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 37);
	}

	public function status_193() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 59
			&& $this->userDetails->tripulacao["res_y"] == 51);
	}

	public function status_194() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 38);
	}

	public function status_195() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 62
			&& $this->userDetails->tripulacao["res_y"] == 52);
	}

	public function status_196() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 39);
	}

	public function status_197() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 80
			&& $this->userDetails->tripulacao["res_y"] == 48);
	}

	public function status_198() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 40);
	}

	public function status_199() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 87
			&& $this->userDetails->tripulacao["res_y"] == 57);
	}

	public function status_200() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 41);
	}

	public function status_201() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 88
			&& $this->userDetails->tripulacao["res_y"] == 48);
	}

	public function status_202() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 42);
	}

	public function status_203() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 97
			&& $this->userDetails->tripulacao["res_y"] == 47);
	}

	public function status_204() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 43);
	}

	public function status_205() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 100
			&& $this->userDetails->tripulacao["res_y"] == 49);
	}

	public function status_206() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 44);
	}

	public function status_207() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 111
			&& $this->userDetails->tripulacao["res_y"] == 49);
	}

	public function status_208() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 45);
	}

	public function status_209() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 136
			&& $this->userDetails->tripulacao["res_y"] == 54);
	}

	public function status_210() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 46);
	}

	public function status_211() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 165
			&& $this->userDetails->tripulacao["res_y"] == 46);
	}

	public function status_212() {
		return $this->status_bool($this->userDetails->ilha["ilha"] == 47);
	}

	public function status_213() {
		return $this->status_bool($this->userDetails->tripulacao["res_x"] == 190
			&& $this->userDetails->tripulacao["res_y"] == 51);
	}

	// habilidades
	public function status_214($pers) {
		return $this->status($this->skills[$pers["cod"]]["chute"], 6, $pers["cod"]);
	}

	public function status_215($pers) {
		return $this->status($this->skills[$pers["cod"]]["chute"], 11, $pers["cod"]);
	}

	public function status_216($pers) {
		return $this->status($this->skills[$pers["cod"]]["chute"], 21, $pers["cod"]);
	}

	public function status_217($pers) {
		return $this->status($this->skills[$pers["cod"]]["santoryuu"], 6, $pers["cod"]);
	}

	public function status_218($pers) {
		return $this->status($this->skills[$pers["cod"]]["santoryuu"], 11, $pers["cod"]);
	}

	public function status_219($pers) {
		return $this->status($this->skills[$pers["cod"]]["santoryuu"], 21, $pers["cod"]);
	}

	public function status_220($pers) {
		return $this->status($this->skills[$pers["cod"]]["corte"], 6, $pers["cod"]);
	}

	public function status_221($pers) {
		return $this->status($this->skills[$pers["cod"]]["corte"], 11, $pers["cod"]);
	}

	public function status_222($pers) {
		return $this->status($this->skills[$pers["cod"]]["corte"], 21, $pers["cod"]);
	}

	public function status_223($pers) {
		return $this->status($this->skills[$pers["cod"]]["tiro"], 6, $pers["cod"]);
	}

	public function status_224($pers) {
		return $this->status($this->skills[$pers["cod"]]["tiro"], 11, $pers["cod"]);
	}

	public function status_225($pers) {
		return $this->status($this->skills[$pers["cod"]]["tiro"], 21, $pers["cod"]);
	}

	public function status_226($pers) {
		return $this->status($this->skills[$pers["cod"]]["fogo"], 6, $pers["cod"]);
	}

	public function status_227($pers) {
		return $this->status($this->skills[$pers["cod"]]["fogo"], 11, $pers["cod"]);
	}

	public function status_228($pers) {
		return $this->status($this->skills[$pers["cod"]]["fogo"], 21, $pers["cod"]);
	}

	public function status_229($pers) {
		return $this->status($this->skills[$pers["cod"]]["agua"], 6, $pers["cod"]);
	}

	public function status_230($pers) {
		return $this->status($this->skills[$pers["cod"]]["agua"], 11, $pers["cod"]);
	}

	public function status_231($pers) {
		return $this->status($this->skills[$pers["cod"]]["agua"], 21, $pers["cod"]);
	}

	// chefes das ilhas
	public function status_232() {
		return $this->status($this->pve["22"], 1);
	}

	public function status_233() {
		return $this->status($this->pve["23"], 1);
	}

	public function status_234() {
		return $this->status($this->pve["24"], 1);
	}

	public function status_235() {
		return $this->status($this->pve["25"], 1);
	}

	public function status_236() {
		return $this->status($this->pve["26"], 1);
	}

	public function status_237() {
		return $this->status($this->pve["27"], 1);
	}

	public function status_238() {
		return $this->status($this->pve["28"], 1);
	}

	public function status_239() {
		return $this->status($this->pve["29"], 1);
	}

	public function status_240() {
		return $this->status($this->pve["30"], 1);
	}

	public function status_241() {
		return $this->status($this->pve["31"], 1);
	}

	public function status_242() {
		return $this->status($this->pve["32"], 1);
	}

	public function status_243() {
		return $this->status($this->pve["33"], 1);
	}

	public function status_244() {
		return $this->status($this->pve["34"], 1);
	}

	public function status_245() {
		return $this->status($this->pve["35"], 1);
	}

	public function status_246() {
		return $this->status($this->pve["36"], 1);
	}

	public function status_247() {
		return $this->status($this->pve["37"], 1);
	}

	public function status_248() {
		return $this->status($this->pve["38"], 1);
	}

	public function status_249() {
		return $this->status($this->pve["39"], 1);
	}

	public function status_250() {
		return $this->status($this->pve["40"], 1);
	}

	public function status_251() {
		return $this->status($this->pve["41"], 1);
	}

	public function status_252() {
		return $this->status($this->pve["42"], 1);
	}

	public function status_253() {
		return $this->status($this->pve["43"], 1);
	}

	public function status_254() {
		return $this->status($this->pve["44"], 1);
	}

	public function status_255() {
		return $this->status($this->pve["45"], 1);
	}

	public function status_256() {
		return $this->status($this->pve["46"], 1);
	}

	public function status_257() {
		return $this->status($this->pve["47"], 1);
	}

	public function status_258() {
		return $this->status($this->pve["48"], 1);
	}

	public function status_259() {
		return $this->status($this->pve["49"], 1);
	}

	public function status_260() {
		return $this->status($this->pve["50"], 1);
	}

	public function status_261() {
		return $this->status($this->pve["51"], 1);
	}

	public function status_262() {
		return $this->status($this->pve["52"], 1);
	}

	public function status_263() {
		return $this->status($this->pve["53"], 1);
	}

	public function status_264() {
		return $this->status($this->pve["54"], 1);
	}

	public function status_265() {
		return $this->status($this->pve["55"], 1);
	}

	public function status_266() {
		return $this->status($this->pve["56"], 1);
	}

	public function status_267() {
		return $this->status($this->pve["57"], 1);
	}

	public function status_268() {
		return $this->status($this->pve["58"], 1);
	}

	public function status_269() {
		return $this->status($this->pve["59"], 1);
	}

	public function status_270() {
		return $this->status($this->pve["60"], 1);
	}

	public function status_271() {
		return $this->status($this->pve["61"], 1);
	}

	public function status_272() {
		return $this->status($this->pve["62"], 1);
	}

	public function status_273() {
		return $this->status($this->pve["63"], 1);
	}

	public function status_274() {
		return $this->status($this->pve["64"], 1);
	}

	public function status_275() {
		return $this->status($this->pve["65"], 1);
	}

	public function status_276() {
		return $this->status($this->pve["66"], 1);
	}

	public function status_277() {
		return $this->status($this->pve["67"], 1);
	}

	public function status_278() {
		return $this->status($this->pve["68"], 1);
	}
}