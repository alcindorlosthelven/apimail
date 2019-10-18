<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 29/03/2018
 * Time: 22:30
 */

namespace app\DefaultApp\Controlleurs;
use app\DefaultApp\Models\TestModel;
use systeme\Controlleur\Controlleur;
class DefaultControlleur extends Controlleur
{
    public function index(){
        $variable['titre']="Acceuil";
        return $this->render("default/index",$variable);
    }
 
}