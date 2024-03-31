<?

abstract class AbstractImport extends Component
{
	/** До починки хука */
	public const PAGE_SIZE = null;

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
			$iPage = $params['page'];
			unset($params['page']);
		} else {
			$iPage = 1;
		}

		$iCount = $this->ComponentImport_Import_Count($params);
		$aFields = $this->ComponentImport_Import_Select($iPage, $params);

		$this->Template_Assign("iCount", $iCount);
		$this->Template_Assign("iPageSize", static::PAGE_SIZE);
		$this->Template_Assign("iPage", $iPage);
		$this->Template_Assign("aFilters", $_REQUEST);
		$this->Template_Assign("aFields", $aFields);
		$this->Template_AddJs($this->Template_GetHost() . "components/import/templates/import.js");
		$this->SetTemplate("default.tpl");
	}
}