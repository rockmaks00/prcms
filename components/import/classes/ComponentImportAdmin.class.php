<?

class ComponentImportAdmin extends Component
{
	protected $oNode = null;

	public function Init()
	{
		$this->SetDefaultAction('default');
		$this->oNode = Router::GetCurrentNode();
		$this->Template_SetPageTitle($this->oNode->getTitle());
		$this->Template_AddTitle($this->oNode->getTitle());
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
