<?php

/** @var Container $container */

use App\AppSettings;
use App\Logger;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

$container['errorHandler'] = function (Container $container) {

	$logger = $container['errorLogger'];

	return function(Request $request, Response $response, Exception $exception) use ($container, $logger) {

		$logger->error($exception);

		if ($container->get(AppSettings::class)->displayErrorDetails) {
			$respData = array(
				'status' => false,
				'error' => $exception->getMessage(),
			);
		} else {
			$respData = array(
				'status' => false,
				'error' => 'An error occured',
			);
		}


		if ($container->settings['displayErrorDetails']) {
			$respData['trace'] = $exception->getTrace();
		}


		return $response->withJson(
			$respData,
			500
		);

	};

};
