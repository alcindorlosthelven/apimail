<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 20/03/2018
 * Time: 18:16
 */

namespace systeme\Routeur;
use systeme\Application\Application;

class Route
{
    private $chemin;
    private $callable;
    private $matches=[];
    private $parametres=array();

    public function __construct($chemin,$callable)
    {
        $this->callable=$callable;
        $this->chemin=trim($chemin,"/");
    }

    public function avec($parametre,$regex){
      $this->parametres[$parametre]=str_replace("(","(?:",$regex);
      return $this;
    }

    public function match($url)
    {
       // var_dump($url);
        $url=trim($url,"/");

        $chemin=preg_replace_callback("#:([\w]+)#",[$this,"parametreMatch"],$this->chemin);
        $regex="#^$chemin$#i";
        if(!preg_match($regex,$url,$matches)){
            return false;
        }
        array_shift($matches);
        $this->matches=$matches;
        return true;
    }

    private function parametreMatch($match){
       if(isset($this->parametres[$match[1]])){
           return "(".$this->parametres[$match[1]].")";
       }
       return "([^/]+)";

    }

    public function call()
    {
        if(is_string($this->callable)){
            $parametres=explode(".",$this->callable);
            $controlleur="app\\".Application::nomApp()."\Controlleurs\\".ucfirst($parametres[0])."Controlleur";
            $action=$parametres[1];
            $controlleur=new $controlleur();
            return call_user_func_array([$controlleur,$action],$this->matches);
        }else{
            return call_user_func_array($this->callable,$this->matches);
        }
    }

    public function getUrl($parametres){
        $chemin=$this->chemin;
        foreach ($parametres as $k => $v){
           $chemin=str_replace(":$k",$v,$chemin);
        }

        return $chemin;
    }

}