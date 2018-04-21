<?php

namespace App\GraphQL;

use App\Exception;
use GraphQL\Type\Definition\InputObjectType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OndraKoupil\Tools\Arrays;

class UniversalMutationResolver {

	/**
	 * Univerzální mutace typu create/edit
	 *
	 * @param array $args Argumenty jdoucí do node v GraphQL
	 * @param string $argsInputName Název datového objektu mezi argumenty
	 * @param string $modelClassName Jméno třídy Eloquent modelu
	 * @param InputObjectType $inputGraphQLType Typ datového objektu mezi argumenty
	 * @param string $argsIdName Volitelně název argumentu představujícího ID
	 *
	 * @return Model Vrací objekt třídy $modelClassName
	 *
	 * @throws Exception
	 */
	static function resolveMutation(
		$args,
		$argsInputName,
		$modelClassName,
		InputObjectType $inputGraphQLType,
		$argsIdName = 'id',
		$nonNullFieldsWhenCreating = null,
		$fieldsThatAreManyToManyRelations = array()
	) {

		$input = $args[$argsInputName];
		if (!$input) {
			throw new Exception('Input object "' . $argsInputName . '" is required among input parameters.');
		}

		$createMode = false;
		if (isset($args[$argsIdName]) and $args[$argsIdName]) {
			/** @var Model $model */
			$model = call_user_func_array(array($modelClassName, 'find'), array($args['id']));
		} else {
			/** @var Model $model */
			$model = new $modelClassName();
			$createMode = true;
		}

		if (!$model) {
			throw new Exception('No resource with this ID was found.');
		}

		$manyManyInverted = array_fill_keys($fieldsThatAreManyToManyRelations, true);

		foreach ($inputGraphQLType->getFields() as $field) {
			$fieldName = $field->name;
			if (isset($input[$fieldName])) {
				if (!isset($manyManyInverted[$fieldName])) {
					$model->setAttribute($fieldName, $input[$fieldName]);
				}
			}
		}


		if ($createMode) {
			if ($nonNullFieldsWhenCreating === null) {
				$nonNullFieldsWhenCreating = array();
				foreach ($inputGraphQLType->getFields() as $field) {
					$name = $field->name;
					$argsIdNameLength = strlen($argsIdName);
					if (substr($name, 0, $argsIdNameLength) === $argsIdName and $name !== $argsIdName) {
						$nonNullFieldsWhenCreating[] = $name;
					}
				}
			}
			if ($nonNullFieldsWhenCreating) {
				foreach ($nonNullFieldsWhenCreating as $nonNullField) {
					if (!$model->getAttributeValue($nonNullField)) {
						throw new Exception('When creating new resource, field "' . $nonNullField . '" can not be empty.');
					}
				}
			}
		}

		$model->save();

		if ($fieldsThatAreManyToManyRelations) {
			foreach ($fieldsThatAreManyToManyRelations as $fieldName) {
				if (isset($input[$fieldName])) {
					/** @var BelongsToMany $relationObject */
					$relationObject = call_user_func_array(array($model, $fieldName), array());
					$relationObject->sync($input[$fieldName]);
				}
			}
		}

		return $model;

	}


	static function resolveDeleteMutation($ids, $retrieve, $modelClassName) {

		$ids = Arrays::arrayize($ids);

		$models = null;
		if ($retrieve) {
			$models = call_user_func_array(array($modelClassName, 'find'), array($ids));
		} else {
			$models = array_map(function($id) {
				return array('id' => $id);
			}, $ids);
		}

		call_user_func_array(array($modelClassName, 'destroy'), array($ids));

		return $models;

	}


}
