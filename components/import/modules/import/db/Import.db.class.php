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

		if (isset($filters['field_creation_date'])) {
			$where = "WHERE `field_creation_date` = ?";
			$aliases[] = $filters['field_creation_date'];
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
				`field_id` int(11) NOT NULL AUTO_INCREMENT,
                `field_group` varchar(250) NOT NULL,
                `field_task` varchar(250) NOT NULL,
                `field_spent_time` float NOT NULL,
                `field_planned_time` float NOT NULL,
                `field_amount` int(11) NOT NULL,
                `field_creation_date` date NOT NULL,
                `field_link` varchar(250) NOT NULL,
				PRIMARY KEY (`field_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";

			return $this->oDb->Query($sql);
		}
	}

	public function Count(array $filters): int
	{
		$data = $this->BuildSelect("COUNT(field_id) AS count", $filters);
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
				`field_group`,
				`field_task`,
				`field_spent_time`,
				`field_planned_time`,
				`field_amount`,
				`field_creation_date`,
				`field_link`
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
				`field_group`=?,
				`field_task`=?,
	            `field_spent_time`=?,
	            `field_planned_time`=?,
	            `field_amount`=?,
	            `field_creation_date`=?,
	            `field_link`=?
			WHERE field_id=?
		";

		return $this->oDb->Query(
			$sql,
			$oField->getGroup(),
			$oField->getTask(),
			$oField->getSpentTime(),
			$oField->getPlannedTime(),
			$oField->getAmount(),
			$oField->getCreationDate(),
			$oField->getLink(),
			$oField->getId()
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

	public function Get(int $id)
	{
		$sTableName = self::tableName();
		$sql = "SELECT * FROM {$sTableName} WHERE field_id=?";

		return $this->oDb->SelectRow($sql, $id);
	}
}
