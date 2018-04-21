<?php

/** @var App $app */

use App\Controllers\TestController;
use Slim\App;

$app->any(
	'/test',
	TestController::class
);
