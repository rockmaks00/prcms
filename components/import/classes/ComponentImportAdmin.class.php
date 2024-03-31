<?

class ComponentImportAdmin extends Component
{
	protected $oNode;
	protected $sAction;
	protected $aParams = [];
	protected $aLang = [];
	protected $sTemplatePath;

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
		$this->AddAction('upload', 'ActionUpload');
	}

	protected function ActionDefault()
	{
		if(!$this->AccessCheck("R")) {
			return;
		}

		$aFields = $this->ComponentImport_Import_Select();

		$this->Template_Assign("aFields", $aFields);
		$this->Template_AddJs($this->Template_GetHost()."components/import/templates/admin/import.js");
		$this->SetTemplate("default.tpl");
	}

	protected function ActionUpload()
	{
		if(!$this->AccessCheck("V")) {
			echo json_encode(['status' => 403]);
		} else {
			if (($handle = fopen($_FILES['csv']['tmp_name'], "r")) !== false) {
				// парсинг заголовка файла / пока не используется
				$header = fgetcsv($handle);

				while (($row = fgetcsv($handle)) !== false) {
					$data[] = $row;
				}

				fclose($handle);

				$this->SaveFields($data);

				echo json_encode(['status' => 200]);
			} else {
				echo json_encode(['status' => 400]);
			}
		}

		// увидел такую реализацию в других местах, но похоже на костыль чтобы не рисовать template
		exit; 
	}

	protected function SaveFields(array $fields): void
	{
		foreach ($fields as $field) {
			$entity = Engine::GetEntity('ComponentImport_Import', null, 'Field');

			$entity->setGroup($field[0]);
			$entity->setTask($field[1]);
			$entity->setSpentTime($field[2]);
			$entity->setPlannedTime($field[3]);
			$entity->setAmount($field[4]);
			$entity->setCreationDate($field[5]);
			$entity->setLink($field[6]);

			$this->ComponentImport_Import_Add($entity);
		}
	}
}
