<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 10/03/2018
 * Time: 16:29
 */

namespace systeme\Database;
use PDO;
use PDOException;
class DbConnection
{

    private $serveur;
    private $nom_base;
    private $utilisateur;
    private $motdepasse;
    private static $pdo;
    private static $connection;

    private $sgbd;

    /**
     * DbConnection constructor.
     * @param string $serveur
     * @param string $nom_base
     * @param string $utilisateur
     * @param string $motdepasse
     */
    public function __construct($infos_datatabase=array(),$sgbd="mysql")
    {
        $this->serveur = $infos_datatabase['serveur'];
        $this->nom_base = $infos_datatabase['nom_base'];
        $this->utilisateur = $infos_datatabase['utilisateur'];
        $this->motdepasse = $infos_datatabase['motdepasse'];
        $this->sgbd=$sgbd;
    }
    /**
     * @return string
     */
    public function getServeur()
    {
        return $this->serveur;
    }

    /**
     * @param string $serveur
     */
    public function setServeur($serveur)
    {
        $this->serveur = $serveur;
    }

    /**
     * @return string
     */
    public function getNomBase()
    {
        return $this->nom_base;
    }

    /**
     * @param string $nom_base
     */
    public function setNomBase($nom_base)
    {
        $this->nom_base = $nom_base;
    }

    /**
     * @return string
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param string $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    /**
     * @return string
     */
    public function getMotdepasse()
    {
        return $this->motdepasse;
    }

    /**
     * @param string $motdepasse
     */
    public function setMotdepasse($motdepasse)
    {
        $this->motdepasse = $motdepasse;
    }

    public function Connection()
    {
        if($this::$pdo ==  null)
        {
            try{
                $con = new PDO("mysql:host={$this->getServeur()};dbname={$this->getNomBase()}",$this->getUtilisateur(),$this->getMotdepasse());
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $e){
                throw  new PDOException($e->getMessage());
            }
            $this::$pdo++;
            $this::$connection=$con;
            return $con;

        }else
        {
            return $this::$connection;

        }


    }

    /*public function jma(){
        try{
             $con=$this->Connection();
             $req="select *from y4w0resdlodi limit 1";
             $res=$con->query($req);
             $data=$res->fetch();
             return $data;
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    */



}