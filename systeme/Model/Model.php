<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 30/03/2018
 * Time: 13:25
 */

namespace systeme\Model;

use systeme\Application\Application;
use systeme\Application\Session;

date_default_timezone_set("America/Port-au-Prince");

class Model extends Session
{

    /**
     * @return null|\PDO
     */
    public static function connection()
    {
        return \systeme\Application\Application::connection();
    }

    /**
     * @param $a
     * @param $sujet
     * @param $contenue
     * @param string $attachement
     * @param string $reply
     * @return string
     */
    public static function envoyerEmail($a, $sujet, $contenue, $attachement = "", $reply = "")
    {
        return Application::envoyerEmail($a, $sujet, $contenue, $attachement, $reply);
    }

    /**
     * @param $objet
     * @return \ReflectionObject
     */
    private function getReflection($objet)
    {
        return new \ReflectionObject($objet);
    }

    /**
     * @param $objet
     * @return string
     */
    private function reqAdd($objet)
    {
        try {
            $sourceReflection = $this->getReflection($objet);
            $nomTable = strtolower($sourceReflection->getShortName());
            $sourceProperties = $sourceReflection->getProperties();
            $champs = "";
            $valeurs = "";
            foreach ($sourceProperties as $sourceProperty) {
                $sourceProperty->setAccessible(true);
                $name = $sourceProperty->getName();
                if ($name != 'id') {
                    $champs .= $name . ",";
                    $valeurs .= ":" . $name . ",";
                }

            }
            $valeurs = substr($valeurs, 0, -1);
            $champs = substr($champs, 0, -1);
            $req = "insert into $nomTable ($champs) VALUES ($valeurs)";
            return $req;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }

    }

    /**
     * @param $objet
     * @return string
     */
    private function reqUpdate($objet)
    {
        try {
            $sourceReflection = $this->getReflection($objet);
            $nomTable = strtolower($sourceReflection->getShortName());
            $sourceProperties = $sourceReflection->getProperties();
            $champs = "";
            foreach ($sourceProperties as $sourceProperty) {
                $sourceProperty->setAccessible(true);
                $name = $sourceProperty->getName();
                if ($name != 'id') {
                    $champs .= $name . "=:$name,";
                }

            }
            $champs = substr($champs, 0, -1);
            $req = "update $nomTable set $champs WHERE id=:id";
            return $req;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }

    }

    /**
     * @param $objet
     * @param string $id
     * @return string
     */
    private function reqFind($objet, $id = "")
    {
        try {
            $sourceReflection = new \ReflectionObject($objet);
            $nomTable = strtolower($sourceReflection->getShortName());
            if ($id == "") {
                $req = "select *from $nomTable";
            } else {
                $req = "select *from $nomTable WHERE id=:id";
            }
            return $req;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }

    }

    /**
     * @param $objet
     * @param string $id
     * @return string
     */
    private function reqDelete($objet, $id = "")
    {
        try {
            $sourceReflection = new \ReflectionObject($objet);
            $nomTable = strtolower($sourceReflection->getShortName());
            if ($id == "") {
                $req = "delete from $nomTable";
            } else {
                $req = "delete from $nomTable WHERE id=:id";
            }
            return $req;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }

    }


    /**
     * @return string
     * @throws \Exception
     */
    public function add()
    {
        try {
            $con = self::connection();
            $params = array();
            $reflection = $this->getReflection($this);
            $proprietes = $reflection->getProperties();
            foreach ($proprietes as $propriete) {
                $propriete->setAccessible(true);
                $name = $propriete->getName();
                $value = $propriete->getValue($this);
                if ($value == "") {
                    $value = 'null';
                }

                if ($name != 'id') {
                    $params[":" . $name] = $value;
                }
            }
            $requete = $this->reqAdd($this);
            $stmt = $con->prepare($requete);
            $res = $stmt->execute($params);
            if ($res) {
                return "ok";
            } else {
                return "no";
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }

    }

    /**
     * @return string
     * @throws \Exception
     */
    public function update()
    {
        try {
            $con = self::connection();
            $params = array();
            $reflection = $this->getReflection($this);
            $proprietes = $reflection->getProperties();
            foreach ($proprietes as $propriete) {
                $propriete->setAccessible(true);
                $name = $propriete->getName();
                $value = $propriete->getValue($this);
                if ($value == "") {
                    $value = 'null';
                }

                $params[":" . $name] = $value;

            }
            $requete = $this->reqUpdate($this);
            $stmt = $con->prepare($requete);
            $res = $stmt->execute($params);
            if ($res) {
                return "ok";
            } else {
                return "no";
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }

    }

    /**
     * @param $id
     * @return null
     * @throws \Exception
     */
    public
    function findById($id)
    {
        try {
            $reflection = $this->getReflection($this);
            $con = self::connection();
            $requete = $this->reqFind($this, 1);
            $stmt = $con->prepare($requete);
            $stmt->execute(array(":id" => $id));
            $res = $stmt->fetchAll(\PDO::FETCH_CLASS, $reflection->getName());
            if (count($res) > 0) {
                return $res[0];
            } else {
                return null;
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public
    function findAll()
    {
        try {
            $reflection = $this->getReflection($this);
            $con = self::connection();
            $requete = $this->reqFind($this);
            $stmt = $con->prepare($requete);
            $stmt->execute();
            $res = $stmt->fetchAll(\PDO::FETCH_CLASS, $reflection->getName());
            return $res;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }


    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public
    function deleteById($id)
    {
        try {
            $con = self::connection();
            $requete = $this->reqDelete($this, $id);
            $stmt = $con->prepare($requete);
            $res = $stmt->execute(array(":id" => $id));
            return $res;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public
    function deleteAll()
    {
        try {
            $con = self::connection();
            $requete = $this->reqDelete($this);
            $stmt = $con->prepare($requete);
            $res = $stmt->execute();
            return $res;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }


}