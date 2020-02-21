<?php
function rcopy($value) {
    global $argv;
	(isset($argv[1])) ? $c = $argv[1] : $c  = ".";
	if (!($value == ".." || $value == ".MyGitLight" || $value == ".git")) {
		if (is_dir($value)) {
			@mkdir(".MyGitLight/staging/" . $value, 0777, true);
			$files = scandir($value);
			foreach ($files as $file) {
				if (!($file == "." || $file == ".MyGitLight" 
					|| $file == ".git" || $file == "..")) {
					rcopy("$value/$file");	
				}	
			}
		} else if (file_exists($value) && $value != ".MyGitLight") {
			copy($value, "./.MyGitLight/staging/$value");	
		}
		return 0;
	} else {
		return 1;
	}
	$dirs = scandir($c);
	foreach ($dirs as $value) {
		rcopy($value);
	}
}