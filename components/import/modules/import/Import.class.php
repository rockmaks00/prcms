<?

class ComponentImport_ModuleImport extends Module
{
	protected $oDb;

	public function Init()
	{
		$this->oDb = Engine::GetDb(__CLASS__);
		$this->oDb->Install();
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

	public function Delete($iId)
	{
		return $this->oDb->Delete($iId);
	}
}
