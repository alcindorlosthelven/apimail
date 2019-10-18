<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 30/03/2018
 * Time: 12:35
 */
/*NB: il est important de ne pas modifier cette classe 
,pour le bon fonctionnement du framework ,sauf si vous savez ce que vous faite.
Alcindor Losthelven Ing Informatique..
*/

namespace systeme\Application;

use systeme\Assette\Assette;
use systeme\Database\DbConnection;
use systeme\Model\Mail;
use systeme\Routeur\Routeur;

class Application extends Session
{


    public static $app = "";
    public static $defaultApp;

    public static $config;

    private static $routeur;

    private static $connection = null;

    public static $dossierProjet;

    private static $assette;

    private static $nomApp;

    private static $configuration = array();

    public static $serveurId;

    public function __construct($configuration)
    {
        $seesion = new Session();
        $_SESSION['database'] = $configuration['database'];
        $_SESSION['configurationEmail'] = $configuration['configurationEmail'];
        self::$configuration = $configuration;
        self::$routeur = new Routeur($configuration['url']);
        self::$dossierProjet = $configuration['dossierProjet'];
        self::$nomApp = $configuration['nomApp'];
        self::$assette = new Assette();
        self::$config = $configuration;
        self::$serveurId = $seesion->getServeurId();

    }

    public static function get($chemin, $fonction, $nom = "")
    {
        return self::$routeur->get($chemin, $fonction, $nom);
    }

    public static function post($chemin, $fonction, $nom = "")
    {
        return self::$routeur->post($chemin, $fonction, $nom);
    }

    public static function genererUrl($nom_route, $parametres = [])
    {
        return self::$routeur->url($nom_route, $parametres);
    }

    public static function redirection($nom_route, $parametres = [])
    {

        self::$routeur->redirection($nom_route, $parametres);
    }


    public static function run()
    {
        try {
            return self::$routeur->run();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }

    }

    public static function connection()
    {
        $con = new DbConnection($_SESSION['database']);
        $con = $con->Connection();
        if (self::$connection == null) {
            self::$connection = $con;
            return self::$connection;
        } else {
            return self::$connection;
        }
    }

    public static function envoyerEmail($from,$a, $sujet, $contenue, $attachement = "", $reply = "")
    {
        $mail = new Mail($_SESSION['configurationEmail']);
        return $mail->envoyer($from,$a, $sujet, $contenue, $attachement, $reply);
    }

    public static function ROOT()
    {
        return $_SERVER['DOCUMENT_ROOT'] . "/" . self::$dossierProjet . "/";
    }

    public static function css($css)
    {
        return self::$assette->css($css);
    }

    public static function js($js)
    {
        return self::$assette->js($js);
    }

    public static function image($image)
    {
        return self::$assette->image($image);
    }

    public static function autre($autre)
    {
        return self::$assette->autre($autre);
    }

    public static function configuration()
    {
        require self::ROOT() . "app/Configuration.php";
    }

    public static function nomApp()
    {
        return self::$nomApp;
    }

    public static function cheminModels()
    {
        return "/app/" . self::$nomApp . "/Models";
    }

    public static function block($bloc)
    {
        return self::$assette->bloc($bloc);
    }

    public static function routing()
    {
        require self::ROOT() . "app/" . self::nomApp() . "/Routing.php";
    }

    public static function imageLocation()
    {
        return self::$assette->imageLocation();
    }

    public static function formatComptable($p)
    {
        if ($p == "") {
            $p = 0;
        }
        $p = str_replace(",", "", $p);
        $r = "#^[0-9]*.?[0-9]+$#";
        if (preg_match($r, $p)) {
            $p = number_format($p, 2, '.', ',');
            return $p;
        } else {
            throw new \Exception("Format incorrect pour prix ou cout");
        }
    }

    public static function validerDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }


    public static function calculAge($anne)
    {
        $anneeAjourdhui = date("Y");
        $age = $anneeAjourdhui - $anne;
        return $age;
    }


    public static function serveurId()
    {
        ob_start();
        system("ipconfig /all");
        $mycom = ob_get_contents();
        ob_clean();
        $findme = "Physical Address";
        $pmac = strpos($mycom, $findme);
        $mac = substr($mycom, ($pmac + 36), 17);
        return $mac;

    }

    public static function CallAPI($method, $url, $data, $headers)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        switch ($method) {
            case "GET":
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "POST":
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                break;
        }

        $response = curl_exec($curl);
        $data = json_decode($response,true);

        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // Check the HTTP Status code
        switch ($httpCode) {
            case 200:
                $error_status = "200: Success";
                return ($data);
                break;
            case 404:
                $error_status = "404: API Not found";
                break;
            case 500:
                $error_status = "500: servers replied with an error.";
                break;
            case 502:
                $error_status = "502: servers may be down or being upgraded. Hopefully they'll be OK soon!";
                break;
            case 503:
                $error_status = "503: service unavailable. Hopefully they'll be OK soon!";
                break;
            default:
                $error_status = "Undocumented error: " . $httpCode . " : " . curl_error($curl);
                break;
        }
        curl_close($curl);
        echo $error_status;
        die;
    }

    //URL Actuel
    public static function urlActuel()
    {
        $url = '' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI'] . '';
        return $url;
    }

}