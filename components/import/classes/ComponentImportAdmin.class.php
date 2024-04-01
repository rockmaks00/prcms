<?
require_once('components\import\classes\AbstractImport.class.php');

class ComponentImportAdmin extends AbstractImport
{
	protected $oNode;
	protected $sAction;
	protected $aParams = [];
	protected $aLang = [];
	protected $sTemplatePath;

	public function Init(): void
	{
		parent::Init();
		$this->sAction = Router::GetActionAdmin();
		$this->aParams = Router::getParams();
		$this->sTemplatePath = $this->Template_GetHost() . "components/admin/templates/default/";
	}

	protected function RegisterActions(): void
	{
		// страницы
		$this->AddAction('default', 'ActionDefault');
		$this->AddAction('edit', 'ActionEdit');

		// ajax
		$this->AddAction('upload', 'ActionUpload');
		$this->AddAction('update', 'ActionUpdate');
		$this->AddAction('delete', 'ActionDelete');
	}

	protected function ActionDefault(): void
	{
		$this->Template_Assign("bEditable", $this->AccessCheck("V"));
		parent::ActionDefault();
	}

	protected function ActionEdit(): void
	{
		$iId = $this->aParams[0];
		
		$aField = $this->ComponentImport_Import_Get($iId);

		if ($aField) {
			$this->Template_AddJs($this->Template_GetHost() . "components/import/templates/edit.js");
			$this->Template_Assign("aField", $aField);
			$this->Template_Assign("sUrl", Config::get("host").'admin/content/'.$this->oNode->getId());
			$this->SetTemplate("edit.tpl");
		} else {
			$this->NotFound();
		}
	}

	protected function ActionUpdate(): void
	{
		if (!$this->AccessCheck("V")) {
			$result['status'] = 403;
		} else {
			$iId = getRequest('field_id', 'post');

			$aField = $this->ComponentImport_Import_Get($iId);

			if (!empty($aField)) {
				$oEntity = Engine::GetEntity('ComponentImport_Import', $aField, 'Field');

				$oEntity->setGroup(getRequest('field_group', 'post'));
				$oEntity->setTask(getRequest('field_task', 'post'));
				$oEntity->setSpentTime(getRequest('field_spent_time', 'post'));
				$oEntity->setPlannedTime(getRequest('field_planned_time', 'post'));
				$oEntity->setAmount(getRequest('field_amount', 'post'));
				$oEntity->setCreationDate(getRequest('field_creation_date', 'post'));
				$oEntity->setLink(getRequest('field_link', 'post'));

				$this->ComponentImport_Import_Update($oEntity);
				$result['status'] = 200;
			} else {
				$result['status'] = 400;
			}
		}

		http_response_code($result['status']);
		echo json_encode($result);
		exit;
	}

	protected function ActionDelete(): void
	{
		if (!$this->AccessCheck("V")) {
			$result['status'] = 403;
		} else {
			$iId = getRequest('field_id', 'post');
			$bDelete = $this->ComponentImport_Import_Delete($iId);
			if ($bDelete) {
				$result['status'] = 200;
			} else {
				$result['status'] = 400;
			}
		}

		http_response_code($result['status']);
		echo json_encode($result);
		exit;
	}

	protected function ActionUpload(): void
	{
		if (!$this->AccessCheck("V")) {
			$result['status'] = 403;
		} else {
			if (($handle = fopen($_FILES['csv']['tmp_name'], "r")) !== false) {
				// парсинг заголовка файла / пока не используется
				$header = fgetcsv($handle);

				while (($row = fgetcsv($handle)) !== false) {
					$data[] = $row;
				}

				fclose($handle);

				$this->SaveFields($data);
				$result['status'] = 200;
			} else {
				$result['status'] = 400;
			}
		}

		http_response_code($result['status']);
		echo json_encode($result);

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
			$entity->setFormattedCreationDate($field[5]);
			$entity->setLink($field[6]);

			$this->ComponentImport_Import_Add($entity);
		}
	}
}
