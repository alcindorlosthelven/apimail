<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 30/03/2018
 * Time: 14:05
 */

namespace systeme\Assette;
use systeme\Application\Application;

class Assette
{

    public function css($css){
        if(Application::$dossierProjet==""){
            return Application::$dossierProjet."/app/".Application::nomApp()."/public/css/".$css.".css";
        }else{
            return "/".Application::$dossierProjet."/app/".Application::nomApp()."/public/css/".$css.".css";
        }
    }

    public function js($js){
        if(Application::$dossierProjet==""){
            return Application::$dossierProjet."/app/".Application::nomApp()."/public/js/".$js.".js";
        }else{
            return "/".Application::$dossierProjet."/app/".Application::nomApp()."/public/js/".$js.".js";
        }
    }

    public function image($image){
        if(Application::$dossierProjet==""){
            return Application::$dossierProjet."/app/".Application::nomApp()."/public/img/".$image;
        }else{
            return "/".Application::$dossierProjet."/app/".Application::nomApp()."/public/img/".$image;
        }
    }

    public function autre($autre){
        if(Application::$dossierProjet==""){
            return Application::$dossierProjet."/app/".Application::nomApp()."/public/".$autre;
        }else{
            return "/".Application::$dossierProjet."/app/".Application::nomApp()."/public/".$autre;
        }
    }

    public static function autres($autre){
        if(Application::$dossierProjet==""){
            return Application::$dossierProjet."/app/".Application::nomApp()."/public/".$autre;
        }else{
            return "/".Application::$dossierProjet."/app/".Application::nomApp()."/public/".$autre;
        }
    }

    public function bloc($bloc){
        require "../app/".Application::nomApp()."/Vues/block/".$bloc.".php";
    }

    public function imageLocation(){
        if(Application::$dossierProjet==""){
            return Application::$dossierProjet."/public/img/";
        }else{
            return "/".Application::$dossierProjet."/public/img/";
        }

    }
}