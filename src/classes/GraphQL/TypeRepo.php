<?php

namespace App\GraphQL;

use App\Exception;
use App\GraphQL\Types\DateTime;
use App\GraphQL\Types\Mutation;
use App\GraphQL\Types\Query;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * @property-read Query $query
 * @property-read Mutation $mutation
 *
 * @property-read DateTime $dateTime
 *
 *
 */
class TypeRepo {

	/**
	 * @var Type[]
	 */
	protected $types;

	/**
	 * @param string $name
	 *
	 * @return Type
	 *
	 * @throws Exception
	 */
	public function __get($name) {
		if (isset($this->types[$name])) {
			return $this->types[$name];
		}

		$type = null;
		$class = __NAMESPACE__ . '\\Types\\' . ucfirst($name);
		if (class_exists($class)) {
			$type = new $class($this);
		} else {
			throw new Exception('Unknown type: ' . $name);
		}

		$this->types[$name] = $type;

		return $type;
	}

}
