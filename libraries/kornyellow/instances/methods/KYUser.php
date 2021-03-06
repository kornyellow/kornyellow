<?php

namespace libraries\kornyellow\instances\methods;

use libraries\korn\server\query\KornQuery;
use libraries\kornyellow\instances\KYMethod;
use libraries\kornyellow\instances\KYInstance;
use libraries\korn\server\query\KornStatement;
use libraries\kornyellow\instances\classes\User;
use libraries\korn\server\query\builder\KornQuerySelect;
use libraries\korn\server\query\builder\KornQueryReplace;

class KYUser extends KYMethod {
	private static string $table = 'user';

	/**
	 * @param User $instance
	 */
	public static function add(KYInstance $instance): int {
		$replace = new KornQueryReplace(self::$table);
		$values  = [
			'u_id'       => $instance->getId(),
			'u_username' => $instance->getUsername(),
			'u_password' => $instance->getPassword(),
		];
		$replace->values($values);

		$query = new KornQuery($replace);

		return $query->insertedID();
	}
	public static function browse(string $query, int $limit = 15, int $offset = 0): array {
		// TODO: Implement browse() method.
	}
	public static function get(int $id): ?User {
		$select = new KornQuerySelect(self::$table);
		$select->where('u_id', $id);

		return self::processObject(new KornQuery($select));
	}
	/**
	 * @return User[]
	 */
	public static function getAll(): array {
		$select = new KornQuerySelect(self::$table);

		return self::processObject(new KornQuery($select), true);
	}
	protected static function processObject(KornQuery $query, bool $isArray = false): User|array|null {
		$result = [];

		$bind = KornStatement::getEmptyFieldsName(self::$table);
		while ($bind = $query->nextBind($bind)) {
			$result[] = new User(
				$bind['u_id'],
				$bind['u_username'],
				$bind['u_password']
			);
			if (!$isArray)
				return $result[0];
		}

		if (count($result) == 0) return null;

		return $result;
	}
	public static function remove(KYInstance $instance): void {
		// TODO: Implement remove() method.
	}
	public static function update(KYInstance $instance): void {
		// TODO: Implement update() method.
	}
	public static function getByUsername(string $username): ?User {
		$select = new KornQuerySelect(self::$table);
		$select->where('u_username', $username);

		return self::processObject(new KornQuery($select));
	}
}
