<?php
namespace Regras\Combate;

class Apostas
{
    /**
     * @var Combate
     */
    protected $combate;

    /**
     * @param Combate
     */
    public function __construct($combate)
    {
        $this->combate = $combate;
    }

    public function apostar()
    {
        //todo
    }

    public function checa_fim_apostas()
    {
        if ($this->combate->userDetails->combate_pvp) {
            $vivos_1 = $this->combate->connection->run("SELECT count(cod) AS total FROM tb_combate_personagens WHERE id = ? AND hp > 0"
            , "i", [$this->combate->userDetails->combate_pvp["id_1"]])->fetch_array()["total"];
            $total_1 = $this->combate->connection->run("SELECT count(cod) AS total FROM tb_combate_personagens WHERE id = ?"
            , "i", [$this->combate->userDetails->combate_pvp["id_1"]])->fetch_array()["total"];

            $vivos_2 = $this->combate->connection->run("SELECT count(cod) AS total FROM tb_combate_personagens WHERE id = ? AND hp > 0"
            , "i", [$this->combate->userDetails->combate_pvp["id_2"]])->fetch_array()["total"];
            $total_2 = $this->combate->connection->run("SELECT count(cod) AS total FROM tb_combate_personagens WHERE id = ?"
            , "i", [$this->combate->userDetails->combate_pvp["id_2"]])->fetch_array()["total"];

            if ($vivos_1 < floor($total_1 / 2) || $vivos_2 < floor($total_2 / 2)) {
                $this->combate->connection->run("UPDATE tb_combate SET fim_apostas = 1 WHERE combate = ?",
                    "i", $this->combate->userDetails->combate_pvp["combate"]);
            }
        }
    }
}
