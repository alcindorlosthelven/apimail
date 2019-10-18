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
        echo DefaultApp::envoyerEmail($to,$sujet,$contenue);


        /*if(count($data->to)==0){
            http_response_code(503);
            echo json_encode(array("message" => "Aucun adresse email trouvé","statut"=>"no"));
            return;
        }*/

        //
       /* if (
            !empty($data->id_classe) &&
            !empty($data->url) && !empty($data->semaine)
        ) {

            if($data->semaine==""){
                http_response_code(503);
                echo json_encode(array("message" => "entrer semaine"));
                return;
            }

            if($data->url==""){
                http_response_code(503);
                echo json_encode(array("message" => "entrer une url correct"));
                return;
            }

            if($data->id_classe==""){
                http_response_code(503);
                echo json_encode(array("message" => "Entrer au moins une classe"));
                return;
            }

            $progression=new Progression();
            $progression->setIdClasse($data->id_classe);
            $progression->setUrl($data->url);
            $progression->setSemaine($data->semaine);
            $m=$progression->add();
            if ($m == "ok") {
                http_response_code(200);
                echo json_encode(array("message" => "Progression ajouter avec success.","status"=>200));
            } else {
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => $m,"status"=>503));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Impossible d'ajouter le progression , donnee manquant","status"=>400));
        }
        */


    }

    public function sendGet(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        $body = [
            "body"=> [
                'Message' => [
                    [
                        'From' => [
                            'Email' => "espacenumerique@haitisolution.net",
                            'Nom' => "Espace Numérique"
                        ],

                        'To' => [
                            [
                                'Email' => "xxxxxx@gmail.com",
                                'Nom' => "xxxxx"
                            ],
                            [
                                'Email' => "yyyyy@gmail.com",
                                'Nom' => "yyyy"
                            ]
                        ],
                        'Sujet' => "sujet",
                        'htmlText' => "message",
                    ]
                ]
            ]
        ];


        echo json_encode($body);

    }

}