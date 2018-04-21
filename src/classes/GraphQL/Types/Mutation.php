<?php

namespace App\GraphQL\Types;

use App\GraphQL\TypeRepo;
use App\GraphQL\UniversalMutation;
use App\GraphQL\UniversalMutationResolver;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class Mutation extends ObjectType {

	function __construct(TypeRepo $repo) {

		$config = array(
			'name' => 'Mutation',
			'fields' => array()
		);

		// $config['fields'] += UniversalMutation::generateMutation(...)

		$config['fields']['pokus'] = array(
			'description' => 'Ukázkový bod v GraphQL',
			'type' => Type::string(),
			'args' => array(
				'name' => Type::nonNull(Type::string()),
			),
			'resolve' => function($previous, $args) {
				return 'Hello ' . $args['name'] . '!';
			}
		);

		parent::__construct($config);

	}

}
