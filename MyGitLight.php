<?php
include "add.php";
include "commit.php";
include "rm.php";
include "log.php";

//Les 3 fonctions ci-dessous gèrent les erreurs
function bad_permissions($file) {
    if (!is_writable($file)) {
        throw new Exception("could not access folder : bad permissions\n");
    }
}

function mygitlight_exists($file) {
    if (file_exists($file)) {
        throw new Exception("this folder already has a MyGitLight\n");
    }
}

function directory_not_exists($file) {
    if (!file_exists($file)) {
        throw new Exception("could not access .MyGitLight\n");
    }
}

//Ternaire qui détermine la valeur de la variable $c
(isset($argv[2])) ? $path = $argv[2] : $path  = ".";

/*Fonction globale qui lance les fonctions de gestion des erreurs et qui crée
**un dossier .MyGitLight et qui copie ce script*/
function myGit() {
    global $argv, $path;
    $init = "";
    $init .= $path;
    try {
        $init .= "/.MyGitLight";
        mygitlight_exists($init);
        mkdir($init, 0777, true);
        bad_permissions($path);
        directory_not_exists($path);
        $init .= "/MyGitLight.php";
        copy($argv[0], $init);
    }
    catch (Exception $e) {
        echo "Message d'erreur : ", $e->getMessage();
        return 1;
    }
}

if ($argv[1] == "init") {
    myGit();
    return 0;
} elseif ($argv[1] == "add") {
    rcopy($argv[2]);
    return 0;
} elseif ($argv[1] == "commit") {
    commit($argv[2]);
    return 0;
} elseif ($argv[1] == "rm") {
    rm();
    return 0;
} elseif ($argv[1] == "log") {
    logMyGitLight();
    return 0;
} else {
    return 1;
}