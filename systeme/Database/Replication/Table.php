<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 12/01/2019
 * Time: 20:52
 */

namespace systeme\Database\Replication;

use systeme\Model\Model;

class Table extends Model
{
    private $nom;
    private static $nom_base;

    public function __construct()
    {
        self::$nom_base = $_SESSION['database']['nom_base'];
    }

    /**
     * @return mixed
     */
    protected function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom_table
     */
    protected function setNom($nom)
    {
        $this->nom = $nom;
    }

    protected static function listeTable()
    {
        $listeTable = array();
        try {

            $con = self::connection();
            $req = "show tables";
            $res = $con->query($req);
            $data = $res->fetchAll(\PDO::FETCH_CLASS, "systeme\\Database\\Replication\\Table");
            foreach ($data as $tb){
                $table = new Table();
                $v="Tables_in_".self::$nom_base;
                $table->setNom($tb->$v);
                $listeTable[]=$table;
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
        return $listeTable;
    }

    protected static function getDonnee($table_name){
        try{
            $con=self::connection();
            $req="select *from $table_name";
            $stmt=$con->prepare($req);
            $stmt->execute();
            $data=$stmt->fetchAll(\PDO::FETCH_OBJ);
            return $data;
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    protected static function deleteData($table_name){
        try{
            $con=self::connection();
            $req="delete from $table_name";
            $stmt=$con->prepare($req);
            $stmt->execute();
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    protected static function insertDonnees($req){
        try{
            $con=self::connection();
            if($con->exec($req)){
                return "ok";
            }else{
                return "no";
            }
        }catch (\Exception $ex){
            return $ex->getMessage();
        }
    }


}