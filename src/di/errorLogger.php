<?php

/** @var Container $container */

use App\AppSettings;
use App\Logger;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

$container['errorLogger'] = function (Container $container) {

	$logger = new Logger('errors', $container['RunID']);
	return $logger;

};
