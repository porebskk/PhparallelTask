<?php

$filePathSuffix = $argv[1];
$fileSuccess = $filePathSuffix . '.success';
$fileError = $filePathSuffix . '.error';
$fileStarted = $filePathSuffix . '.started';

$fileEndReference = $fileError;
register_shutdown_function(function() use ($fileEndReference){
    file_put_contents($fileEndReference, '');
});

$cmd = base64_decode($argv[2]);

file_put_contents($fileStarted, time());

exec($cmd, $output, $return);

$fileEndReference = $fileSuccess;

if ($return === 0) {
    file_put_contents($fileSuccess, time());
} else {
    file_put_contents($fileError, json_encode($output));
}