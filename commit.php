<?php
//Récupère le contenu des fichiers
function get_file($path) {
    $content = file_get_contents($path);
    return $content;
}

//Remplit le tableau avec le nom des fichiers en clé et le contenu en valeur
function fill_tab(&$array, $path) {
    $array[$path]=  get_file($path);
}

/*Remplit le tableau avec les dossiers et sous-dossiers vides en clé et
**un contenu vide en valeur*/
function fill_tab_void(&$array, $path){
    $array[$path] = "";
}

/*explore les dossiers et les fichiers passés en paramètres et exécute
**les fonctions ci-dessus*/
function explorer(&$array, $path) {
    if (is_file($path)) {
        fill_tab($array, $path);
    } elseif (is_dir($path)) {
        $folder = scandir($path);
        fill_tab_void($array, $path);
        foreach ($folder as $value) {
            if ($value != "." && $value != "..") {
                explorer($array, $path . "/" . $value);
            }
        }
    }
}

//écrit le nom du fichier et son contenu dans une archive passée en paramètre
function write_file($open, $key, $value) {
    static $i = 0;
    if ($i == 0) {
        fwrite($open, $key);
        fwrite($open, "¤¤¤");
        $write = fwrite($open, $value);
        $i++;
    } else {
        fwrite($open, "¤¤¤");
        fwrite($open, $key);
        fwrite($open, "¤¤¤");
        $write = fwrite($open, $value);
    }
    return $write;
}

/*répète la fonction write_file pour écrire chaque clé et chaque contenu dans
**une archive dont le nom sera l'ID, symbolisé par $i, et lance la fonction
** write_commit_log à la fin*/
function loop_write($loop, $message) {
    if (!file_exists(".MyGitLight/commits/commit_log.txt")) {
        $i = 1;
    } else {
        $content = file_get_contents(".MyGitLight/commits/commit_log.txt");
        $commit_array = explode("\n", "$content");
        $count_array = count($commit_array);
        $i = $count_array;
    }
    if (!is_dir(".MyGitLight/commits")) {
        mkdir(".MyGitLight/commits");
    }
    $open = fopen(".MyGitLight/commits/$i", "w+");
    foreach ($loop as $key => $value) {
        write_file($open, $key, $value);
    }
    if ($open != false) {
        fclose($open);
    }
    write_commit_log($message);
}

/*crée un fichier commit_log s'il n'existe pas et écrit le message de
**chaque commit à la suite*/
function write_commit_log($message) {
        $commit_log = fopen(".MyGitLight/commits/commit_log.txt", "a+");
        fwrite($commit_log, $message);
        fwrite($commit_log, "\n");
        fclose($commit_log);
}

//crée une archive contenant les fichiers passés en paramètres
function my_tar(&$array, $message){
    explorer($array, ".MyGitLight/staging");
    loop_write($array, $message);
}

//fonction finale lançant toutes les autres en chaine
function commit($message) {
            global $array;
            $array = [];
            my_tar($array, $message);
}