<?php

require_once __DIR__ . "/Includes/BladeOne/BladeOne.php";

use eftec\bladeone\BladeOne;

class Componentes
{
    public static $blade;

    /**
     * Run the blade engine. It returns the result of the code.
     *
     * @param string|null $view      The name of the cache. Ex: "folder.folder.view" ("/folder/folder/view.blade")
     * @param array       $variables An associative arrays with the values to display.
     * @return string
     * @throws \Exception
     */
    public static function render($componente = null, $variables = []) : string
    {
        if (! self::$blade) {
            $compoentes_dir = __DIR__ . "/Componentes";
            $cache_dir = __DIR__ . '/ComponentesCache';
            // MODE_DEBUG allows to pinpoint troubles.
            self::$blade = new BladeOne($compoentes_dir, $cache_dir, BladeOne::MODE_DEBUG);
        }

        return self::$blade->run($componente, $variables);
    }
}
