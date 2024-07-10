<?php

/**
 * Created by PhpStorm.
 * User: ivan.miranda
 * Date: 02/10/2017
 * Time: 08:21
 */
class Alerts
{

    /**
     * @var UserDetails
     */
    private $userDetails;

    /**
     * @var mywrap_con
     */
    private $connection;

    function __construct($userDetails, $connection)
    {
        $this->userDetails = $userDetails;
        $this->connection = $connection;
    }

    public function get_alert($class = "")
    {
        return "<span class=\"label label-danger label-alert $class\">!</span>";
    }

    public function has_alert_trip_sem_distribuir_atributo($pers)
    {
        return $pers["lvl"] > 2 && $pers["pts"];
    }

    public function has_alert_trip_sem_classe($pers)
    {
        return $pers["lvl"] > 3 && ! $pers["classe"];
    }

    public function has_alert_trip_sem_profissao($pers)
    {
        return $pers["lvl"] > 5 && ! $pers["profissao"];
    }

    public function has_alert_trip_sem_distribuir_haki($pers)
    {
        return $pers["haki_pts"];
    }

    public function has_alert_sem_equipamento($pers)
    {
        return $pers["lvl"] >= 50 && (! $pers["cod_acessorio"] || $this->connection->run(
            "SELECT count(*) AS total FROM tb_personagem_equipamentos
					 WHERE cod = ? AND (`1` IS NULL OR `2` IS NULL OR `3` IS NULL OR `4` IS NULL OR `5` IS NULL OR `6` IS NULL OR `7` IS NULL OR `8` IS NULL)",
            "i", array($pers["cod"]))->fetch_array()["total"]);
    }

    function destroy()
    {
        $this->userDetails = null;
        $this->connection = null;
    }
}
