<?php

namespace App\GraphQL\Types;

use App\Exception;
use App\GraphQL\TypeRepo;
use App\GraphQL\UniversalQuery;
use App\GraphQL\UniversalQueryResolver;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class Query extends ObjectType {

	public function __construct(TypeRepo $repo) {

		$config = array(
			'name' => 'Query',
			'description' => 'The root query',
			'fields' => array(),
		);

		// $config['fields'] += UniversalQuery::generateRootConfigForEntity(...);

		$config['fields']['pokus'] = array(
			'description' => 'Ukázkový bod v GraphQL',
			'type' => Type::string(),
			'resolve' => function() {
				return 'Hello world!';
			}
		);

		parent::__construct($config);
	}

}
