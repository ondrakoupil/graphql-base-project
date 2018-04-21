<?php


use App\AppSettings;
use App\Controllers\GraphQLController;
use App\GraphQL\Schema;
use Slim\Container;

/** @var Container $container */
$container[GraphQLController::class] = function(Container $container) {

	$c = new GraphQLController(
		$container['Eloquent'],
		$container[Schema::class],
		$container[AppSettings::class],
		$container['errorLogger']
	);
	return $c;

};
