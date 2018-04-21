<?php

namespace App\Controllers;

use LS\Exception;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class BaseJsonController {

	function __invoke(Request $request, Response $response, $args) {

		$payload = $this->run($request, $response, $args);

		return $response->withJson(
			array('status' => true, 'data' => $payload)
		);
	}

	abstract function run(Request $request, Response $response, $args);

	function response(Response $response, $data) {
		return $response->withJson(
			array(
				"status" => true,
				"data" => $data,
			)
		);
	}

}
