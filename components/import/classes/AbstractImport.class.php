<?

abstract class AbstractImport extends Component
{
    protected $oNode;

    public function Init(): void
    {
        $this->SetDefaultAction('default');
		$this->oNode = Router::GetCurrentNode();
    }

    protected function ActionDefault(): void
	{
		$params = $_REQUEST;

		if (isset($params['page'])) {
			$page = $params['page'];
			unset($params['page']);
		}

		$aFields = $this->ComponentImport_Import_Select($params, $page);

		$this->Template_Assign("aFilters", $_REQUEST);
		$this->Template_Assign("aFields", $aFields);
		$this->Template_AddJs($this->Template_GetHost() . "components/import/templates/import.js");
		$this->SetTemplate("default.tpl");
	}
}