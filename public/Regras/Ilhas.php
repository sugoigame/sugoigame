<?php
namespace Regras;

class Ilhas
{
    public static function get_ilha($cod)
    {
        return \Utils\Data::load("mundo")["ilhas"][$cod];
    }

    public static function get_ilhas_envolta($x, $y, $distancia = 2)
    {
        return array_filter(\Utils\Data::load("mundo")["ilhas"], function ($ilha) use ($x, $y, $distancia) {
            return $ilha["x"] >= $x - $distancia && $ilha["x"] <= $x + $distancia && $ilha["y"] >= $y - $distancia && $ilha["y"] >= $y + $distancia;
        });
    }

    public static function has_ilha_envolta($x, $y, $distancia = 2)
    {
        return count(self::get_ilhas_envolta($x, $y, $distancia)) > 0;
    }

    public static function get_ilha_by_coord($x, $y)
    {
        return \Utils\Data::find_inside("mundo", "ilhas", ["x" => $x, "y" => $y]);
    }
}
