<?php

use PhparallelTask\Promise;

require_once  __DIR__ . '/vendor/autoload.php';

$str = new \PhparallelTask\Thread\Thread();

/** @var Promise[] $jobs */
$jobs = [];
$jobs[] = $str->run(PHP_BINARY . ' wait.php 5');
$jobs[] = $str->run(PHP_BINARY . ' wait.php 2');
$jobs[] = $str->run(PHP_BINARY . ' wait.php 6');

foreach ($jobs as $job) {
    $job->waitForStart();
}
echo 'started all' . PHP_EOL;
foreach ($jobs as $job) {
    $job->waitForFinish();
    echo 'Done:' . $job->getCmd(). PHP_EOL;
}

echo 'done';