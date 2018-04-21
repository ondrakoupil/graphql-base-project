<?php

namespace App\GraphQL;

use GraphQL\Type\Schema as BaseSchema;
use App\GraphQL\TypeRepo;

class Schema extends BaseSchema {

	/**
	 * @var TypeRepo
	 */
	protected $repo;

	function __construct(TypeRepo $repo) {
		$this->repo = $repo;
		parent::__construct(
			array(
				'query' => $repo->query,
				'mutation' => $repo->mutation
			)
		);

		 //$this->assertValid();
	}

}
