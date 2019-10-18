<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 24/08/2018
 * Time: 14:35
 */

namespace systeme\Application;


class Configuration
{
    private static  $configuration=array();
    public static function addConfiguration($configuration,$nom){
        self::$configuration[$nom]=$configuration;
    }

    public static function getConfiguration(){
        return self::$configuration;
    }
}