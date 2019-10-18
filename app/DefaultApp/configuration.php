<?php
//configuration base de donnee
$database = array(
    "serveur" => "localhost",
    "nom_base" => "los-framework",
    "utilisateur" => "root",
    "motdepasse" => "ruthamar1991"
);

//configuration email
$from=array(
    "email"=>"",
    "nom"=>""
);

$configurationEmail = array(
    "host" =>"",
    "utilisateur" =>"",
    "motdepasse" =>"",
    "port"=>465,
    "from"=>$from
);
//fin configuration email

$configuration = array(
    "url" => $_GET['url'],
    "database" => $database,
    "configurationEmail"=>$configurationEmail,
    "dossierProjet" => "mail_api",
    "nomApp" => "DefaultApp"
);
define("AUTHORIZENET_LOG_FILE", "phplog");
\systeme\Application\Configuration::addConfiguration($configuration,"DefaultApp");
