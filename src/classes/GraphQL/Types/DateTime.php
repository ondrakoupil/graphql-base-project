<?php

namespace App\GraphQL\Types;

use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use DateTime as BaseDateTime;
use InvalidArgumentException;
use App\GraphQL\TypeRepo;
use OndraKoupil\Tools\Time;

class DateTime extends ScalarType {

	public $name = 'DateTime';

	public $description = 'Datum ve formátu vhodném pro new Date()';

	/**
	 * @var TypeRepo
	 */
	private $repo;

	public function __construct(TypeRepo $repo) {
		parent::__construct();
		$this->repo = $repo;
	}


	public function serialize($value) {
		return Time::convert($value, Time::JSON);
	}

	public function parseValue($value) {
		return Time::convert($value, Time::PHP);
	}

	public function parseLiteral($valueNode) {
		if ($valueNode instanceof StringValueNode or $valueNode instanceof IntValueNode) {
			return Time::convert($valueNode->value, Time::PHP);
		}
		throw new InvalidArgumentException('Given node is not of type string or int.');
	}

}
