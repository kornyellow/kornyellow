<?php

namespace libraries\korn\server\query;

use mysqli_stmt;
use libraries\korn\utils\KornDebug;
use libraries\korn\server\database\KornConnection;

class KornStatement {
	public static function getFieldsName(string $table): array {
		$statement = self::prepare('SELECT * FROM `'.$table.'`');

		$result = $statement->get_result();
		$fields = $result->fetch_fields();

		$fieldsName = [];
		foreach ($fields as $field)
			$fieldsName[] = $field->name;

		return $fieldsName;
	}
	public static function prepare(string $query): mysqli_stmt {
		$connection = KornConnection::getDatabase();
		$statement  = $connection->prepare($query);

		if (!$statement)
			KornDebug::printError('ERROR: Failed to prepare query', $connection->error);

		$statement->execute();

		return $statement;
	}
	public static function getEmptyFieldsName(string $table): array {
		$emptyFields = [];

		$statement = self::prepare('SELECT * FROM `'.$table.'`');

		$result = $statement->get_result();
		$fields = $result->fetch_fields();

		foreach ($fields as $field)
			$emptyFields[$field->name] = null;

		return $emptyFields;
	}
}
