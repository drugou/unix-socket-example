<?php declare(strict_types=1);
require_once 'vendor/autoload.php';

$application = new \App\Application();
$application->boot();
$application->execClient();