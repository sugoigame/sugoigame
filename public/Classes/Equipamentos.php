<?php

class Equipamentos {

	/**
	 * @var mywrap_con
	 */
	private $connection;

	/**
	 * Equipamentos constructor.
	 * @param mywrap_con $connection
	 */
	public function __construct(mywrap_con $connection) {
		$this->connection = $connection;
	}

	public function create_equipamento($equipamento) {
		$result = $this->connection->run(
			"INSERT INTO tb_item_equipamentos (item, img, cat_dano, b_1, b_2, categoria, nome, descricao, lvl, treino_max, slot, requisito) VALUE 
			(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
			"iiiiiissiiii", array(
				$equipamento["item"],
				$equipamento["img"],
				$equipamento["cat_dano"],
				$equipamento["b_1"],
				$equipamento["b_2"],
				$equipamento["categoria"],
				$equipamento["nome"],
				$equipamento["descricao"],
				$equipamento["lvl"],
				$equipamento["treino_max"],
				$equipamento["slot"],
				$equipamento["requisito"]
			)
		);

		return $result->last_id();
	}

	function destroy(){
		$this->connection = null;
	}
}