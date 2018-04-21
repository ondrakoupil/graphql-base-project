<?php

namespace App\Middleware;

use App\Exception;
use Slim\Http\Request;
use Slim\Http\Response;

class LazyCorsMiddleware {


	function __invoke(Request $request, Response $response, callable $next) {

		if ($request->getMethod() === 'OPTIONS') {
			$newResponse =
				$response
					->withHeader('Access-Control-Allow-Origin', '*')
					->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
					->withHeader('Access-Control-Allow-Methods', 'GET, POST');

			return $newResponse;
		}

		/** @var Response $nextResponse */
		$nextResponse = $next($request, $response);

		$newNextResponse =
			$nextResponse
				->withHeader('Access-Control-Allow-Origin', '*')
				->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
				->withHeader('Access-Control-Allow-Methods', 'GET, POST');

		return $newNextResponse;

	}

}
