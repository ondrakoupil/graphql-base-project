<?php


use App\GraphQL\TypeRepo;
use Slim\Container;

/** @var Container $container */
$container[TypeRepo::class] = function(Container $container) {

	$c = new TypeRepo(
	);

	return $c;

};
