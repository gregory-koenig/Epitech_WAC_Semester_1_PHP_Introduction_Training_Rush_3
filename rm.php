<?php
function rm(...$array) {
	foreach ($array as $value) {
		if(find_file($value)) {
		    if(is_dir($value)) {
                rmdir($value);
                rmdir("GitMyLight/...");
            } else {
                unlink($value);
                unlink("GitMyLight/...");
            }
        }
	}
}

function find_file($value) {
	if(file_exists($chemin_fichier_suivi) && file_exists($chemin_dossier_courant)) {
		return true;
	} else {
		return false;
	}
}