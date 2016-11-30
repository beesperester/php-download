<?php

error_reporting( E_ALL );
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

use Beesperester\Chunky\Facades\Chunky;

$url = '';
$target = '/var/www/html/downloads/test.mp4';

Chunky::download($url, $target);

/*$contents = file_get_contents($url);
file_put_contents('/var/www/html/tests/test.mp4', $contents);*/
