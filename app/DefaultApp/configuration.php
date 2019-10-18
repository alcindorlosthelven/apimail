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
    "email"=>"apimail@haitisolution.net",
    "nom"=>"apimail"
);

$configurationEmail = array(
    "host" =>"mail.haitisolution.net",
    "utilisateur" =>"apimail@haitisolution.net",
    "motdepasse" =>"ruthamar1991",
    "port"=>465,
    "from"=>$from
);
//fin configuration email

$configuration = array(
    "url" => $_GET['url'],
    "database" => $database,
    "configurationEmail"=>$configurationEmail,
    "dossierProjet" => "apimail",
    "nomApp" => "DefaultApp"
);
\systeme\Application\Configuration::addConfiguration($configuration,"DefaultApp");
