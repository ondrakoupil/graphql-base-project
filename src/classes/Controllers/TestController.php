<?php

namespace App\Controllers;

use App\AppSettings;
use App\Exception;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class TestController extends BaseJsonController {

	/**
	 * @var AppSettings
	 */
	protected $appSettings;

	/**
	 * @param AppSettings $appSettings
	 */
	public function __construct(Container $container) {
		$this->appSettings = $container->get(AppSettings::class);
		$container->get('Eloquent'); // To create and boot Eloquent
	}


	function run(Request $request, Response $response, $args) {

		if ($request->getParam('error')) {
			throw new Exception('There was an [error] parameter, so I throw an error.');
		}

		return array('a' => 10);

 	}

}
