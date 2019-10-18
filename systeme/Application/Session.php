<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 18/08/2018
 * Time: 15:40
 */

namespace systeme\Application;

session_start();
class Session
{
    private static $serveurId;
    public function __construct()
    {
        ob_start();
        system("ipconfig /all");
        $mycom = ob_get_clean();
       // ob_clean();
        $findme = "Physical Address";
        $pmac = strpos($mycom, $findme);
        $mac = substr($mycom, ($pmac + 36), 17);
        self::$serveurId=$mac;

    }
    protected function getServeurId(){
        return self::$serveurId;
    }
}