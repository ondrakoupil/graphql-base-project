<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class UniversalMutation {

	static public function generateMutation($resourceName, InputObjectType $inputType, ObjectType $outputType, $modelClass, $manyManyFields = array()) {

		return array(
			'save' . ucfirst($resourceName)   => self::generateConfigForSave($resourceName, $inputType, $outputType, $modelClass, $manyManyFields),
			'delete' . ucfirst($resourceName) => self::generateConfigForDelete($resourceName, $outputType, $modelClass),
		);

	}

	static public function generateConfigForSave($resourceName, InputObjectType $inputType, ObjectType $outputType, $modelClass, $manyManyFields = array()) {
		return array(
			'description' => 'Creates or modifies a ' . $resourceName . '. Use $id parameter to modify existing one, omit it to create.',
			'args'        => array(
				'id'          => Type::id(),
				$resourceName => Type::nonNull($inputType),
			),
			'type'        => $outputType,
			'resolve'     => function ($previous, $args) use ($modelClass, $inputType, $manyManyFields, $resourceName) {
				return UniversalMutationResolver::resolveMutation(
					$args,
					$resourceName,
					$modelClass,
					$inputType,
					'id',
					null,
					$manyManyFields
				);
			},
		);
	}

	static public function generateConfigForDelete($resourceName, ObjectType $outputType, $modelClass) {

		return array(
			'description' => 'Deletes a ' . $resourceName . ' - either single one, or multiple ones. Set $retreive to true if you wish to return full details of the deleted items, otherwise can be returned only IDs of deleted items.',
			'args'        => array(
				'ids'      => Type::nonNull(Type::listOf(Type::id())),
				'retrieve' => array(
					'type'    => Type::boolean(),
					'default' => false,
				),
			),
			'type'        => Type::listOf($outputType),
			'resolve'     => function ($previous, $args) use ($modelClass) {
				return UniversalMutationResolver::resolveDeleteMutation($args['ids'], isset($args['retrieve']) ? $args['retrieve'] : false, $modelClass);
			},
		);

	}


}
