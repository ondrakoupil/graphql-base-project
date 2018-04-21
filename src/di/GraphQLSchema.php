<?php


use App\GraphQL\Schema;
use App\GraphQL\TypeRepo;
use Slim\Container;

/** @var Container $container */
$container[Schema::class] = function(Container $container) {

	/** @var TypeRepo $typeRepo */
	$typeRepo = $container->get(TypeRepo::class);

	$schema = new Schema($typeRepo);

	return $schema;

};
