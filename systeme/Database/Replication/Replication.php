<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 13/01/2019
 * Time: 09:28
 */

namespace systeme\Database\Replication;
use ReflectionObject;
class Replication extends Table
{
    protected static function getDonneeToJson()
    {

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        $v = array();
        $listeTable = self::listeTable();
        foreach ($listeTable as $table) {
            $nom_table = $table->getNom();
            $v[$nom_table] = Table::getDonnee($nom_table);
        }
        $v['statut']="success";
        return json_encode($v);
    }

    private static function putData($objet,$nomTable)
    {
        try{
            $sourceReflection = new ReflectionObject($objet);
            $sourceProperties = $sourceReflection->getProperties();
            $champs="";
            $valeurs="";
            foreach ($sourceProperties as $sourceProperty) {
                $sourceProperty->setAccessible(true);
                $name = $sourceProperty->getName();
                $value = $sourceProperty->getValue($objet);
                if($value==""){
                    $value='null';
                }

                $champs.=$name.",";
                if($value=='null'){
                    $valeurs.="$value,";
                }else{
                    $valeurs.="'$value',";
                }
            }
            $valeurs=substr($valeurs,0,-1);
            $champs=substr($champs,0,-1);
            $req="insert into $nomTable ($champs) VALUES ($valeurs)";
            $m=Table::insertDonnees($req);
            return $m;
        }catch (\Exception $ex){
            return $ex->getMessage();
        }

    }

    private static function activerCle(){
        try{
            $con=self::connection();
            $req="SET FOREIGN_KEY_CHECKS = 1";
            $stmt=$con->prepare($req);
            if($stmt->execute()){
                return "ok";
            }else{
                return "no";
            }
        }catch (\Exception $ex){
            return $ex->getMessage();
        }
    }

    private static function desactiverCle(){
        try{
            $con=self::connection();
            $req="SET FOREIGN_KEY_CHECKS = 0";
            $stmt=$con->prepare($req);
            if($stmt->execute()){
                return "ok";
            }else{
                return "no";
            }
        }catch (\Exception $ex){
            return $ex->getMessage();
        }
    }

    private static function data($url){
        $response=array();
        try{

            $curl = curl_init();
            $opts = [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
            ];
            curl_setopt_array($curl, $opts);
            $data = curl_exec($curl);
            $infos=curl_getinfo($curl);

            $response['statut']=$infos['http_code'];
            $response['reponse']=$data;

           return $response;
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    protected static function synchroniser($url)
    {
        $message="ok";
        $rs="";
        try{
            self::desactiverCle();
            $reponse=self::data($url);
            if($reponse['statut']!=200){
                if($reponse['statut']=404){
                    $message="Resource introuvable pour l'url : ".$url;
                }elseif($reponse['statut']=0){
                    $message="Url incorrect...";
                }else{
                    $message=$reponse['reponse'];
                }
            }else{
                $donnees = json_decode($reponse['reponse']);
                $listeTable = Table::listeTable();
                if(isset($donnees->statut)){
                    if($donnees->statut=="success"){
                        foreach ($listeTable as $table) {
                            $nom_table = $table->getNom();
                            Table::deleteData($nom_table);
                            $objets=$donnees->$nom_table;
                            foreach ($objets as $ligne){
                                $m=self::putData($ligne,$nom_table);
                                if($m!="ok"){
                                    $message=$m;
                                    return $message;
                                }
                            }
                        }
                    }else{
                        $message="resource trouver non compatible...";
                    }
                }else{
                    $message="resource trouver non compatible...";
                }
            }
            self::activerCle();
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }

        return $message;
    }
}