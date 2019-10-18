<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 18/10/2019
 * Time: 12:52
 */

namespace app\DefaultApp\Controlleurs;


use app\DefaultApp\DefaultApp;

class V1Controlleur
{

    public function send(){
        echo DefaultApp::envoyerEmail("serveurlos@gmail.com,los","test","contenue test");
    }

}