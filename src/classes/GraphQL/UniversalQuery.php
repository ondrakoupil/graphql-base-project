<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Model;

class UniversalQuery {

	static function generateCountResolvers($fieldNames, $modelAttrAffix = '_count', $fieldAffix = 'Count') {
		$ret = array();

		foreach ($fieldNames as $fieldName) {
			$ret[($fieldName . $fieldAffix)] = UniversalQuery::generateCountResolverConfig($fieldName, $modelAttrAffix);
		}

		return $ret;
	}

	static function generateCountResolverConfig($relationName, $modelAttrAffix = '_count') {
		return array(
			'type' => Type::int(),
			'description' => 'Returns count of ' . ucfirst($relationName) . ' without actually resolving and retrieving them from DB.',
			'resolve' => function(Model $model, $args, $context, ResolveInfo $info) use ($relationName, $modelAttrAffix) {
				return UniversalQueryResolver::resolveCountField($model, $relationName, $modelAttrAffix);
			}
		);
	}

	/**
	 * @param string $modelClassName
	 * @param string $argIdName
	 *
	 * @return \Closure Funkce vhodná jako resolver pro Query pro jeden resource
	 */
	static function generateSingleRootResolver($modelClassName, $argIdName = 'id') {
		return function($parent, $args, $context, ResolveInfo $info) use ($modelClassName, $argIdName) {
			return UniversalQueryResolver::resolveSingleRootWithCounts(
				$modelClassName,
				$info,
				$args[$argIdName]
			);
		};
	}

	/**
	 * @param $modelClassName
	 * @param string $countAffix
	 *
	 * @return \Closure Funkce vhodná jako resolver pro Query pro více resourců najednou
	 */
	static function generateMultipleRootsResolver($modelClassName, $countAffix = 'Count') {
		return function($parent, $args, $context, ResolveInfo $info) use ($modelClassName, $countAffix) {
			return UniversalQueryResolver::resolveMultipleRootsWithCounts($modelClassName, $info, $countAffix);
		};
	}


	/**
	 * @param string $modelClassName
	 * @param Type $type
	 * @param string $singleFieldName
	 * @param string $pluralFieldName
	 *
	 * @return array
	 */
	static function generateRootConfigForEntity($modelClassName, Type $type, $singleFieldName, $pluralFieldName) {
		return array(
			$singleFieldName => array(
				'type' => $type,
				'args' => array(
					'id' => Type::nonNull(Type::id()),
				),
				'description' => 'Returns a single ' . ucfirst($singleFieldName) . ' by its ID.',
				'resolve' => UniversalQuery::generateSingleRootResolver($modelClassName),
			),
			$pluralFieldName => array(
				'type' => Type::listOf($type),
				'description' => 'Returns all ' . ucfirst($pluralFieldName),
				'resolve' => UniversalQuery::generateMultipleRootsResolver($modelClassName),
			),
		);
	}

}
