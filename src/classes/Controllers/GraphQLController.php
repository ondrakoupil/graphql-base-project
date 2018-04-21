<?php

namespace App\Controllers;

use LS\AppSettings;
use LS\Exception;
use App\GraphQL\Schema;
use LS\Logger;
use GraphQL\Error\Debug;
use GraphQL\Error\Error;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use OndraKoupil\Tools\Arrays;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class GraphQLController  {

	/**
	 * @var Schema
	 */
	protected $schema;

	/**
	 * @var AppSettings
	 */
	private $appSettings;

	/**
	 * @var Logger
	 */
	private $errorLogger;

	function __construct($bootedEloquent, Schema $schema, AppSettings $appSettings, Logger $errorLogger) {
		$this->schema = $schema;
		$this->appSettings = $appSettings;
		$this->errorLogger = $errorLogger;
	}

	function __invoke(Request $request, Response $response, $args) {

		$query = $request->getParam('query');
		if (!$query) {
			throw new Exception('No query was specified.');
		}

		$variables = Arrays::arrayize($request->getParam('variables'));
		$result = GraphQL::executeQuery($this->schema, $query, null, null, $variables);

		$debugMode = false;
		if ($this->appSettings->displayErrorDetails) {
			$debugMode = Debug::INCLUDE_DEBUG_MESSAGE;
		}

		if ($result->errors) {
			$this->errorLogger->error(
				print_r(
					array_map(
						function(Error $error) {
							return Error::formatError($error);
						},
						$result->errors
					),
					true
				)
			);
		}

		return $response->withJson($result->toArray($debugMode), $result->errors ? 500 : 200);

	}


}
