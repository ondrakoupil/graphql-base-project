<?php

/** @var App $app */

use App\Controllers\GraphQLController;
use Slim\App;

$app->any(
	'/graphql',
	GraphQLController::class
);
