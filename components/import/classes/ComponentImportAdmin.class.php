<?

class ComponentImportAdmin extends Component
{
	protected $oNode = null;
	protected $sAction = null;
	protected $aParams = [];
	protected $aLang = [];
	protected $sTemplatePath = null;

	public function Init()
	{
		$this->SetDefaultAction('default');
		$this->oNode = Router::GetCurrentNode();
		$this->sAction = Router::GetActionAdmin();
		$this->aParams = Router::getParams();
		$this->sTemplatePath = $this->Template_GetHost() . "components/admin/templates/default/";
	}

	protected function RegisterActions()
	{
		$this->AddAction('default', 'ActionDefault');
		$this->AddAction('submit', 'ActionSubmit');
	}

	protected function ActionDefault()
	{
		$this->SetTemplate("default.tpl");
	}

	protected function ActionSubmit()
	{
	}
}
