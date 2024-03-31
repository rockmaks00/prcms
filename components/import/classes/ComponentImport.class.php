<?
require_once('components\import\classes\AbstractImport.class.php');

class ComponentImport extends AbstractImport
{
	public function Init(): void
	{
		parent::Init();
		$this->Template_SetPageTitle($this->oNode->getTitle());
		$this->Template_AddTitle($this->oNode->getTitle());
	}

	protected function RegisterActions(): void
	{
		$this->AddAction('default', 'ActionDefault');
	}
}
