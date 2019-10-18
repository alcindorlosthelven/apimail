<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 13/01/2019
 * Time: 09:28
 */

namespace app\DefaultApp\Models\Replication;

class Replication extends \systeme\Database\Replication\Replication
{
    public static function getDonneeToJson()
    {
        return parent::getDonneeToJson();
    }

    public static function synchroniser($url)
    {
        return parent::synchroniser($url);
    }
}