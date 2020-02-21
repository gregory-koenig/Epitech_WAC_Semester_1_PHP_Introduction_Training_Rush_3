<?php
function logMyGitLight()
{
    $content = file_get_contents(".MyGitLight/commits/commit_log.txt");
    $commit_array = explode("\n", "$content");
    foreach ($commit_array as $id => $message) {
        $id = $id + 1;
        if ($message == NULL) {
            return;
        } else {
            echo "$id adding $message\n";
            $id++;
        }
    }
}