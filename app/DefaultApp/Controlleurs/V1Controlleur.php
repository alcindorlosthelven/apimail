<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 18/10/2019
 * Time: 12:52
 */

namespace app\DefaultApp\Controlleurs;


use app\DefaultApp\DefaultApp;
use systeme\Application\Application;

class V1Controlleur
{
    public function send(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $data = json_decode(file_get_contents("php://input"),true);
        $message=$data['body']['message'];
        $from=$message['from'];
        $to=$message['to'];
        $sujet=$message['sujet'];
        $contenue=$message['htmlText'];
        $m=DefaultApp::envoyerEmail($from,$to,$sujet,$contenue);
        if($m=="ok"){
            http_response_code(200);
            echo json_encode(array("message" => count($to)."Email envoyer avec succes","status"=>"ok"));
        }else{
            http_response_code(503);
            echo json_encode(array("message" => $m,"status"=>"no"));
        }
    }

    public function sendGet(){
        $body = [
            "body"=> [
                'message' => [
                        'from' => [
                            'email' => "espacenumerique@haitisolution.net",
                            'nom' => "Espace Numérique"
                        ],

                        'to' => [
                            [
                                'email' => "alcindorlos@gmail.com",
                                'nom' => "alcindor losthelven"
                            ],
                            [
                                'email' => "serveurlos@gmail.com",
                                'nom' => "serveur los"
                            ]
                        ],
                        'sujet' => "Apimail",
                        'htmlText' => "Email envoyer depuis api mail",
                ]
            ]
        ];
        $reponse=Application::CallAPI("POST","https://apimail.haitisolution.net/apimail/v1/send",$body,array());
        var_dump($reponse);
    }

}