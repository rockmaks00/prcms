<?
class ComponentImport_ModuleImport_DbImport extends Db
{
	protected static function tableName(): string
	{
		return Config::Get("db.prefix") . "import_csv";
	}

	protected function Pagination(string $sql, int $page): string
	{
		$pageSize = AbstractImport::PAGE_SIZE;
			if (!empty($pageSize)) {
			$sql .= " LIMIT {$pageSize}";

			if (!empty($page)) {
				$offset = ($page - 1) * $pageSize;
				$sql .= " OFFSET {$offset}";
			}
		}

		return $sql;
	}

	protected function BuildSelect(string $select, array $filters): array
	{
		$sTableName = self::tableName();
		$aliases = [];

		if (isset($filters['creation_date'])) {
			$where = "WHERE `creation_date` = ?";
			$aliases[] = $filters['creation_date'];
		}

		return [
			'sql' => "SELECT {$select} FROM `{$sTableName}` {$where}",
			'aliases' => $aliases,
		];
	}

	public function Install()
	{
		$sTableName = self::tableName();

		if (!$this->oDb->CheckTableExists($sTableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `{$sTableName}` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
                `group` varchar(250) NOT NULL,
                `task` varchar(250) NOT NULL,
                `spent_time` float NOT NULL,
                `planned_time` float NOT NULL,
                `amount` int(11) NOT NULL,
                `creation_date` date NOT NULL,
                `link` varchar(250) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";

			return $this->oDb->Query($sql);
		}
	}

	public function Count(array $filters): int
	{
		$data = $this->BuildSelect("COUNT(id) AS count", $filters);
		$sql = $data['sql'];
		$aliases = $data['aliases'];

		$result = $this->oDb->SelectRow($sql, ...$aliases);

		return $result['count'];
	}

	public function Select(int $page, array $filters): array
	{
		$data = $this->BuildSelect("*", $filters);
		$sql = $this->Pagination($data['sql'], $page);
		$aliases = $data['aliases'];

		return $this->oDb->Select($sql, ...$aliases);
	}

	public function Add(ComponentImport_ModuleImport_EntityField $oField)
	{
		$sTableName = self::tableName();

		$sql = "INSERT INTO `{$sTableName}` (
				`group`,
				`task`,
				`spent_time`,
				`planned_time`,
				`amount`,
				`creation_date`,
				`link`
			) 
			VALUES(?, ?, ?, ?, ?, ?, ?)
		";

		return $this->oDb->Query(
			$sql,
			$oField->getGroup(),
			$oField->getTask(),
			$oField->getSpentTime(),
			$oField->getPlannedTime(),
			$oField->getAmount(),
			$oField->getCreationDate(),
			$oField->getLink()
		);
	}

	public function Update(ComponentImport_ModuleImport_EntityField $oField)
	{
		$sTableName = self::tableName();

		$sql = "UPDATE `{$sTableName}` SET 
				`group`=?,
				`task`=?,
	            `spent_time`=?,
	            `planned_time`=?,
	            `amount`=?,
	            `creation_date`=?,
	            `link`=?,
			WHERE id=?
		";

		return $this->oDb->Query(
			$sql,
			$oField->getGroup(),
			$oField->getTask(),
			$oField->getSpentTime(),
			$oField->getPlannedTime(),
			$oField->getAmount(),
			$oField->getCreationDate(),
			$oField->getLink()
		);
	}

	public function Delete(int $id): bool
	{
		$sTableName = self::tableName();

		$sql = "DELETE FROM `{$sTableName}` WHERE field_id=?";

		if ($this->oDb->Query($sql, $id)) {
			return true;
		}

		return false;
	}
}
