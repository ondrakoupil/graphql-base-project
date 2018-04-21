<?php

/** @var Container $container */

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

$container['notFoundHandler'] = function(Container $container) {

	return function(Request $request, Response $response) use ($container) {

		return $response->withJson(
			array(
				'status' => false,
				'error' => 'There is no such an endpoint in this application.',
			),
			404
		);

	};

};
