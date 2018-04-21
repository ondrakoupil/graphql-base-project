<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UniversalQueryResolver {

	/**
	 * @param string $modelClass
	 * @param ResolveInfo $info
	 * @param int $id
	 * @param string $countAffix
	 *
	 * @return Model|null
	 */
	static function resolveSingleRootWithCounts($modelClass, ResolveInfo $info, $id, $countAffix = 'Count') {
		$subFields = $info->getFieldSelection(0);
		$countedRelations = array();
		$affixLength = strlen($countAffix) * -1;
		foreach ($subFields as $fieldName => $true) {
			if ($true and substr($fieldName, $affixLength) === $countAffix) {
				$countedRelations[] = substr($fieldName, 0, $affixLength);
			}
		}

		if ($countedRelations) {
			return call_user_func_array(
				array($modelClass, 'withCount'),
				array($countedRelations)
			)->find($id);
		} else {
			return call_user_func_array(
				array($modelClass, 'find'),
				array($id)
			);
		}
	}

	/**
	 * @param Model $model
	 * @param string $relationName
	 * @param string $modelAttrAffix
	 *
	 * @return number
	 */
	static function resolveCountField(Model $model, $relationName, $modelAttrAffix = '_count') {
		$modelAttributeName = $relationName . $modelAttrAffix;
		if (isset($model->$modelAttributeName)) {
			return $model->$modelAttributeName;
		}
		return $model->$relationName()->count();
	}

	/**
	 * @param string $modelClass
	 * @param ResolveInfo $info
	 * @param string $countAffix
	 *
	 * @return Collection
	 */
	static function resolveMultipleRootsWithCounts($modelClass, ResolveInfo $info, $countAffix = 'Count') {
		$subFields = $info->getFieldSelection(0);
		$countedRelations = array();
		$affixLength = strlen($countAffix) * -1;
		foreach ($subFields as $fieldName => $true) {
			if ($true and substr($fieldName, $affixLength) === $countAffix) {
				$countedRelations[] = substr($fieldName, 0, $affixLength);
			}
		}
		if ($countedRelations) {
			return call_user_func_array(
				array($modelClass, 'withCount'),
				array($countedRelations)
			)->get();
		} else {
			return call_user_func_array(
				array($modelClass, 'all'),
				array()
			);
		}
	}

	static function resolveEnumField($modelClass, $fieldName, $filterEmpty = true) {

		/** @var Collection $collection */
		$collection = call_user_func_array(array($modelClass, 'select'), array($fieldName))->distinct()->orderBy($fieldName)->get();
		$array = array_map(
			function($item) use ($fieldName) {
				return $item[$fieldName];
			},
			$collection->toArray()
		);
		if ($filterEmpty) {
			$array = array_filter($array);
		}
		return $array;
	}

}
