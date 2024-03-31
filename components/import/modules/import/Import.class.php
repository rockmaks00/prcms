<?

class ComponentImport_ModuleImport extends Module
{
	protected $oDb;

	public function Init()
	{
		$this->oDb = Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}

	public function Count(array $filters): int
	{
		return $this->oDb->Count($filters);
	}

	public function Select(int $page, array $filters): array
	{
		return $this->oDb->Select($page, $filters);
	}

	public function Add(ComponentImport_ModuleImport_EntityField $oField)
	{
		$iId = $this->oDb->Add($oField);
		if ($iId) {
			$oField->setId($iId);
		}

		return $oField;
	}

	public function Update(ComponentImport_ModuleImport_EntityField $oField)
	{
		return $this->oDb->Update($oField);
	}

	public function Delete(int $id)
	{
		return $this->oDb->Delete($id);
	}

	public function Get(int $id)
	{
		return $this->oDb->Get($id);
	}
}
