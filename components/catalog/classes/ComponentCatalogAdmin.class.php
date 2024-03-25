<?
class ComponentCatalogAdmin extends Component {
	protected $oNode=null;
	protected $sAction=null;
	protected $aParams=array();
	protected $aLang=array();
	private function IncludeStyles()
	{
		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		$this->Template_AddJs($this->sTemplatePath."assets/ckeditor/ckeditor.js");
		$this->Template_AddJs($this->sTemplatePath."assets/JQuery.attr_select.js");
	}
	public function Init(){
		$this->SetDefaultAction('default'); // Устанавливает экшн по умолчанию
		$this->oNode=Router::GetCurrentNode(); // Текущий редактируемый раздел
		$this->sAction=Router::GetActionAdmin(); // Текущий экшн
		$this->aParams=Router::getParams(); // Массив параметров
		$sTemplatePath=$this->Template_GetHost()."components/admin/templates/default/";
		$this->sTemplatePath=$sTemplatePath; // Путь до шаблона админки
		
		$this->Template_Assign("isIm", (Router::GetCurrentNode()->GetParam("im")=="Y"?true:false));
	}
	protected function RegisterActions() {
		$this->AddAction('default','ActionDefault');
		/*------GROUP BEGIN------*/
		$this->AddAction('group_edit','ActionGroupAdd');
		$this->AddAction('group_add','ActionGroupAdd');
		$this->AddAction('group_activate','ActionGroupActivate');
		$this->AddAction('group_deactivate','ActionGroupDeactivate');
		$this->AddAction('group_delete','ActionGroupDelete');
		$this->AddAction('group','ActionDefault');
		/*------GROUP END------*/
		
		/*------ITEM BEGIN------*/
		$this->AddAction('item_edit','ActionItemAdd');
		$this->AddAction('item_add','ActionItemAdd');
		$this->AddAction('item_activate','ActionItemActivate');
		$this->AddAction('item_deactivate','ActionItemDeactivate');
		$this->AddAction('item_delete','ActionItemDelete');
		/*------ITEM END------*/
		
		$this->AddAction('attributes','ActionAttributes');
		$this->AddAction('csv','ActionCsv');
		$this->AddAction('changecsv','ActionChangeCsv');
		/*
$this->AddAction('updateparent','ActionUpdateParent');
		$this->AddAction('addparent','ActionAddParent');
		$this->AddAction('deleteattr','ActionAttrDelete');
		$this->AddAction('activateattr','ActionActivateAttr');
		$this->AddAction('deactivateattr','ActionDeactivateAttr');
		$this->AddAction('deletecatalogattr','ActionDeleteCatalogAttr')
*/
	}
	
	protected function ActionDefault() {
		$iParentId=round(Router::GetParam(0));
		$aGroups=$this->ComponentCatalog_Catalog_GetGroupsByNode($this->oNode->getId(), $iParentId);
		if ($iParentId) $oParent = $this->ComponentCatalog_Catalog_GetGroupById($iParentId);
		
		$aItems=$this->ComponentCatalog_Catalog_GetItemsByGroup($iParentId);
		
		$this->Template_Assign("aGroups", $aGroups);
		$this->Template_Assign("oParent", $oParent);
		$this->Template_Assign("aItems", $aItems);
		$this->Template_Assign("sFormTitle", $this->oNode->getTitle());
		$this->SetTemplate("groups_list.tpl");
	}
	
	
	/*------GROUP BEGIN------*/
	protected function ActionGroupAdd(){
		$this->Template_Assign("sFormTitle", "Добавление группы");
		$this->Template_Assign("sFormAction", "group_add");
		$this->IncludeStyles();

		$iParentId = $this->ComponentCatalog_Catalog_GetGroupParentIdById(round(Router::GetParam(1)));
		$aGroups=$this->ComponentCatalog_Catalog_GetGroupsByNode($this->oNode->getId(), $iParentId?$iParentId:0);

		$this->Template_Assign("aGroups", $aGroups);
		
		if (getRequest("sub")){
			if (getRequest('id')){
				$oGroup=$this->ComponentCatalog_Catalog_GetGroupById(getRequest('id'));
			}else{
				$oGroup=Engine::GetEntity('ComponentCatalog_Catalog', null, 'Group');
			}
			$this->ActionGroupUpdate($oGroup);
		}else{
			$oGroup=$this->ComponentCatalog_Catalog_GetGroupById(intval(Router::getParam(0)));
			if ($oGroup){
				$_REQUEST['id']=$oGroup->getId();
				$_REQUEST['title']=$oGroup->getTitle();
				$_REQUEST['image']=$oGroup->getImage();
				$_REQUEST['desc']=$oGroup->getDesc();
				$_REQUEST['parent']=$oGroup->getParent();
				$_REQUEST['active']=$oGroup->getActive();
			}else{
				$_REQUEST['active']=1;
				$_REQUEST['parent']=round(Router::GetParam(1));
			}
		}
		$this->SetTemplate("group_form.tpl");
	}
	protected function ActionGroupUpdate(ComponentCatalog_ModuleCatalog_EntityGroup $oGroup) {
		if (isset($_FILES['image']) and is_uploaded_file($_FILES['image']['tmp_name'])) {				
			if ($sFileName=$this->Image_UploadImage($_FILES['image'],"catalog")) {	
				$oGroup->setImage($sFileName);
			} else {
				$this->Template_AddMessage("Ошибка!","Не получилось загрузить фото!");
				return false;
			}
		}
		$_REQUEST['image']=$oGroup->getImage();		
		$oGroup->setTitle(getRequest('title'));
		$oGroup->setActive(getRequest('active'));
		$oGroup->setDesc(getRequest('desc'));
		$oGroup->setParent(getRequest('parent'));
		$oGroup->setNode($this->oNode->getId());
		if ($oGroup->getParent()) $sufix="group/".$oGroup->getParent()."/";
		if ($oGroup->getId()) $this->ComponentCatalog_Catalog_GroupUpdate($oGroup);
		else $oGroup=$this->ComponentCatalog_Catalog_GroupAdd($oGroup);
		
		if (!getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/".$sufix);
		else header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/group_edit/".$oGroup->getId()."/".$sufix);
	}
	
	protected function ActionGroupDeactivate(){
		$iId=intval(Router::GetParam(0));
		if ($this->ComponentCatalog_Catalog_GroupDeactivate($iId)){ 
			$result['state']="success";
			$result['msg']=0;
		}
		else $result['state']="error";
		echo json_encode($result);
		exit;
	}
	
	protected function ActionGroupActivate(){
		$iId=intval(Router::GetParam(0));
		if ($this->ComponentCatalog_Catalog_GroupActivate($iId)){ 
			$result['state']="success";
			$result['msg']=1;
		}
		else $result['state']="error";
		echo json_encode($result);
		exit;
	}
	
	protected function ActionGroupDelete(){
		$this->ComponentCatalog_Catalog_GroupDelete(intval(Router::GetParam(0)));
		if ($_SERVER['HTTP_REFERER']) header("Location: ".$_SERVER['HTTP_REFERER']);
		else header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
	}
	/*------GROUP END------*/
	
	
	
	/*------ITEM BEGIN------*/
	protected function ActionItemAdd()
	{
		$this->Template_Assign("sFormTitle", "Добавление элемента");
		$this->Template_Assign("sFormAction", "item_add");
		$this->IncludeStyles();
		if (getRequest("sub")){
			if (getRequest('id')){
				$oItem=$this->ComponentCatalog_Catalog_GetItemById(getRequest('id'));
				}
			else {
				$oItem=Engine::GetEntity('ComponentCatalog_Catalog',0,"Item");
				$oItem->setCatalogId(Router::GetParam(0));
				}
			$this->ActionItemUpdate($oItem);
		} else {

			$oItem=$this->ComponentCatalog_Catalog_GetItemById(intval(Router::getParam(0)));
			$aAttributes = $this->ComponentCatalog_Catalog_GetAttributesByNode($this->oNode->getId());
			
			if ($oItem){
				$_REQUEST['id']=$oItem->getId();
				$_REQUEST['count']=$oItem->getCount();
				$_REQUEST['price']=$oItem->getPrice();
				$_REQUEST['title']=$oItem->getTitle();
				$_REQUEST['catalog']=$oItem->getCatalog();
				$_REQUEST['image']=$oItem->getImage();
				$_REQUEST['sort']=$oItem->getSort();
				$_REQUEST['active']=$oItem->getActive();
				foreach($aAttributes as $oAttribute){
					$sValue = $this->ComponentCatalog_Catalog_GetAttributeValue($oAttribute->getId(), $oItem->getId());
					if ($sValue) $oAttribute->setDefault($sValue->getValue());
				}
				/*
if ($aAttrs)
				foreach ($aAttrs as $oAttr=>$iId) {
					$_REQUEST[$iId['attr_title']]=$iId['attritem_value'];
				}
*/
			}else{
				$_REQUEST['active']=1;
				//$aAttrs=$this->ComponentCatalog_Catalog_GetAttrByCatalog(Router::getParam(0));
			}
		}
		$this->Template_Assign("aAttributes", $aAttributes);
		$this->SetTemplate("item_form.tpl");
	}
	protected function ActionItemUpdate(ComponentCatalog_ModuleCatalog_EntityItem $oItem)	{
		if (isset($_FILES['image']) and is_uploaded_file($_FILES['image']['tmp_name'])) {				
			if ($sFileName=$this->Image_UploadImage($_FILES['image'],"item")) {	
				$oItem->setImage($sFileName);
			} else {
				$this->Template_AddMessage("Ошибка!","Не получилось загрузить фото!");
				return false;
			}
		}
		$_REQUEST['image']=$oItem->getImage();
		$oItem->setTitle(getRequest('title'));
		$oItem->setCount(getRequest('count'));
		$oItem->setPrice(getRequest('price'));
		$oItem->setSort(getRequest('sort'));
		$oItem->setActive(getRequest('active'));
		$oItem->setNode($this->oNode->getId());

		if ($oItem->getId()) {
			$this->ComponentCatalog_Catalog_ItemUpdate($oItem);
			/*
$aAttrs=$this->ComponentCatalog_Catalog_GetAttrsByItem($oItem->getId());
			foreach ($aAttrs as $oAttr) 
			{
				$oAttr['attritem_value']=getRequest($oAttr['attr_title']);
				$this->ComponentCatalog_Catalog_ItemAttrUpdate($oAttr);
			}
*/
		}
		else {
			$oItem->setGroup(round(Router::getParam(1)));
			$oItem = $this->ComponentCatalog_Catalog_ItemAdd($oItem);
			/*
$aAttrs=$this->ComponentCatalog_Catalog_GetAttrByCatalog(Router::GetParam(0));
			foreach ($aAttrs as $oAttr) 
			{
				$oAttr->setType($oItem->getId());
				$this->ComponentCatalog_Catalog_AddItemAttr($oAttr);
			}
*/
		}
		
		$aAttributes = $this->ComponentCatalog_Catalog_GetAttributesByNode($this->oNode->getId());
		foreach($aAttributes as $oAttribute){
			$oValue = $this->ComponentCatalog_Catalog_GetAttributeValue($oAttribute->getId(), $oItem->getId());
			if (!$oValue) $oValue = Engine::GetEntity('ComponentCatalog_Catalog', 0, "Value");
			$oValue->setValue(getRequest('attribute_'.$oAttribute->getId()));
			$oValue->setOwner($oItem->getId());
			$oValue->setAttribute($oAttribute->getId());
			if ($oValue->getId()){
				$this->ComponentCatalog_Catalog_ValueUpdate($oValue);
			}else{
				$oValue = $this->ComponentCatalog_Catalog_ValueAdd($oValue);
			}
		}
		
		if (!getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/group/".$oItem->getGroup()."/");
		else {header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/item_edit/".$oItem->getId()."/"); exit;};
	}
	
	protected function ActionItemActivate()
	{
		$iId=intval(Router::GetParam(0));
		if ($this->ComponentCatalog_Catalog_ItemActivate($iId)){
			$result['state']="success";
			$result['msg']=1;
		}
		else $result['state']="error";
		echo json_encode($result);
		exit;
	}
	protected function ActionItemDeactivate(){
		$iId=intval(Router::GetParam(0));
		if ($this->ComponentCatalog_Catalog_ItemDeactivate($iId)){ 
			$result['state']="success";
			$result['msg']=0;
		}
		else $result['state']="error";
		echo json_encode($result);
		exit;
	}
	protected function ActionItemDelete()
	{
		$this->ComponentCatalog_Catalog_ItemDelete(intval(Router::GetParam(0)));
		if ($_SERVER['HTTP_REFERER']) header("Location: ".$_SERVER['HTTP_REFERER']);
		else header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/show/");
	}
	/*------ITEM END------*/
	
	
	
	
	/*------ATTRIBUTES BEGIN------*/
	protected function ActionAttributes() {
		
		if (getRequest("sub")){
			$this->ActionAtributesUpdate();
		} else {
			$aAttributes = $this->ComponentCatalog_Catalog_GetAttributesByNode($this->oNode->getId());
			$aAttributes[] = Engine::GetEntity('ComponentCatalog_Catalog', null, 'Attribute');
			$aAttributes[] = Engine::GetEntity('ComponentCatalog_Catalog', null, 'Attribute');
			$aAttributes[] = Engine::GetEntity('ComponentCatalog_Catalog', null, 'Attribute');
			$aAttributes[] = Engine::GetEntity('ComponentCatalog_Catalog', null, 'Attribute');
			$aAttributes[] = Engine::GetEntity('ComponentCatalog_Catalog', null, 'Attribute');
		}


		$this->Template_Assign("aAttributes", $aAttributes);
		$this->Template_Assign("sFormTitle", "Дополнительные свойства элемента");
		$this->SetTemplate("attributes_form.tpl");
	}
	
	protected function ActionAtributesUpdate()	{
		foreach($_POST['attribute']['id'] as $i=>$id){
			if ($id){
				$oAttribute = $this->ComponentCatalog_Catalog_GetAttributeById(intval($id));
			}else{
				$oAttribute = Engine::GetEntity('ComponentCatalog_Catalog', null, 'Attribute');
			}
			$oAttribute->setTitle($_POST['attribute']['title'][$i]);
			$oAttribute->setName($_POST['attribute']['name'][$i]);
			$oAttribute->setDefault($_POST['attribute']['default'][$i]);
			$oAttribute->setType($_POST['attribute']['type'][$i]);
			$oAttribute->setSort($_POST['attribute']['sort'][$i]);
			$oAttribute->setActive(round($_POST['attribute']['active'][$i]));
			
			if ($oAttribute->getId()) {
				$this->ComponentCatalog_Catalog_AttributeUpdate($oAttribute);
			}else{
				if ($oAttribute->getTitle() || $oAttribute->getName()){
					$oAttribute->setNode($this->oNode->getId());
					$this->ComponentCatalog_Catalog_AttributeAdd($oAttribute);
				}
			}
		}
		foreach($_POST['attribute']['delete'] as $id){
			$this->ComponentCatalog_Catalog_AttributeDelete($id);
		}
		
		if (!getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		else{ header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/attributes/"); exit; }
	}
	
	/*------ATTRIBUTES END------*/
	protected function ActionCsv() {
		require("components/catalog/classes/ActionCsv.php");
	}
	protected function ActionChangeCsv() {
		$this->Template_Assign("sFormTitle", "Изменить структуру CSV файла");
		$this->SetTemplate("structure_csv.tpl");
		$this->Template_AddJs($this->sTemplatePath."assets/js/JQuery.tableDnD.js");
		$this->Template_AddJs($this->sTemplatePath."assets/js/tableDnD.js");
		if (getRequest("sub"))
		{
			$data=getRequest('Active');
			$aType=getRequest('type');
			if (isset($data))
			{
				$this->ComponentCatalog_Catalog_ClearCsv($this->oNode->getId());
				
				foreach ($data as $key => $value) {
					if (((int)$key)==0){
						$iPos=$this->ComponentCatalog_Catalog_getStatick($key,"search");
						
					} else {
						if ($value=="on")
						$iPos=$key;
					}
					mpr($iPos);
					$this->ComponentCatalog_Catalog_setCsvTemplate($iPos,$aType[$key],$this->oNode->getId());
				}
			}
		if (!getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/CSV/");
		else{ header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/"); exit; }
		}
		$aTitles=$this->ComponentCatalog_Catalog_getCsvAttr($this->oNode->getId());
		$this->Template_Assign("aTitles", $aTitles);
		
	}
	/*
	public function ActionDeleteCatalogAttr()
	{
		$this->ComponentCatalog_Catalog_CatalogAttrDelete(intval(Router::GetParam(0)));
		header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/edit/".Router::getParams(0));
	}
	protected function ActionAddParent()
	{
		$this->Template_Assign("sFormTitle", "Добавление ".$this->aLang['item_genitive']);
		$this->Template_Assign("sFormAction", "addparent");
		$this->IncludeStyles();
		if (getRequest("sub")){
			if (getRequest('id')) $oCatalog=$this->ComponentCatalog_Catalog_GetParentById(getRequest('id'));
			else $oCatalog=Engine::GetEntity('ComponentCatalog_Catalog');
			$this->ActionUpdateParent($oCatalog);
		}else{
			$oCatalog=$this->ComponentCatalog_Catalog_GetParentById(intval(Router::getParam(0)));
			if ($oCatalog){
				$_REQUEST['id']=$oCatalog->getId();
				$_REQUEST['title']=$oCatalog->getTitle();
				$_REQUEST['image']=$oCatalog->getImage();
				$_REQUEST['desc']=$oCatalog->getDesc();
				$_REQUEST['parent']=Router::getParam(0);
			}else{
				$_REQUEST['active']=1;
			}
		}
		$this->SetTemplate("catalog_form.tpl");
	}
		protected function ActionUpdateParent(ComponentCatalog_ModuleCatalog_EntityCatalog $oCatalog) {
		if (isset($_FILES['image']) and is_uploaded_file($_FILES['image']['tmp_name'])) {				
			if ($sFileName=$this->Image_UploadImage($_FILES['image'],"catalog")) {	
				$oCatalog->setImage($sFileName);
			} else {
				$this->Template_AddMessage("Ошибка!","Не получилось загрузить фото!");
				return false;
			}
		}
		$_REQUEST['image']=$oCatalog->getImage();		
		$oCatalog->setTitle(getRequest('title'));
		$oCatalog->setDesc(getRequest('desc'));
		$oCatalog->setActive(getRequest('active'));
		$oCatalog->setNode($this->oNode->getId());
		$oCatalog->setParent(Router::getParam(0));
		if ($oCatalog->getId()) $this->ComponentCatalog_Catalog_UpdateParent($oCatalog);
		else {

			$oCatalog=$this->ComponentCatalog_Catalog_AddParent($oCatalog);}

		if (!getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/show/".$oCatalog->getParent()."/");
		else header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/edit/".$oCatalog->getId()."/");
	}
	
	protected function ActionShow()
	{
		$this->Template_Assign("sFormTitle", "Элементы ".$this->aLang['Itemadd_genitive']);
		$this->Template_Assign("sFormAction", "show");
		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		$this->Template_AddJs($this->sTemplatePath."assets/ckeditor/ckeditor.js");
		$aItems=$this->ComponentCatalog_Catalog_GetItemsByCatalog(Router::GetParam(Count(Router::GetParams())-1));
		$aCatalogs=$this->ComponentCatalog_Catalog_GetCatalogsByParent(Router::GetParam(Count(Router::GetParams())-1));
		$this->Template_Assign("aCatalogs", $aCatalogs);
		$this->Template_Assign("aItems", $aItems);
		$this->SetTemplate("catalog_items_list.tpl");
	}
	
	// CSV :: [START]
	protected function ActionCsv(){
		$this->Template_Assign("sFormTitle", "Выгрузка из CSV");
		$this->IncludeStyles();

		$sTemplatePath=$this->Template_GetHost()."components/admin/templates/default/";
		$this->sTemplatePath=$sTemplatePath;

		$this->Template_AddCss($sTemplatePath."assets/data-tables/DT_bootstrap.css");

		$this->Template_AddJs($sTemplatePath."assets/data-tables/jquery.dataTables.js");
		$this->Template_AddJs($sTemplatePath."assets/data-tables/DT_bootstrap.js");
		$this->Template_AddJs($sTemplatePath."assets/js/csv.js");

		if ( $_FILES['csv'] ) {
			$aCsv = $this->Csv_GetCsv('ComponentCatalog_Catalog', 'Item');
			foreach ($aCsv as $key => $oItem) {
				$this->ComponentCatalog_Catalog_AddItem($oItem);
			}
			$this->Template_Assign("aCsv", $aCsv);
		}

		$this->SetTemplate("catalog_csv.tpl");
	}
	// CSV :: [END]

	protected function ActionUpdateAttr(ComponentCatalog_ModuleCatalog_EntityAttr $oAttr)
	{
		$oAttr->setTitle(getRequest("attrtitle"));
		$oAttr->setCatalog(getRequest('id'));
		$oAttr->setType(getRequest("combotype"));
		$oAttr->setParam(getRequest("combonews"));
		$oAttr->setActive(getRequest("attractive"));
		if (getRequest('combocopy'))
		{
			$aStruct=$this->ComponentCatalog_Catalog_GetAttrByCatalog(getRequest("combocopy"));
			$this->ComponentCatalog_Catalog_AddCatalogStruct($aStruct,Router::getParam(0));
		} else 
		if ($oAttr->getId()) $this->ComponentCatalog_Catalog_UpdateAttr($oAttr);
		else $oAttr=$this->ComponentCatalog_Catalog_AddAttr($oAttr);
		if (!getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		else header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/edit/");
	}
	protected function ActionAttrDelete()
	{
		$this->ComponentCatalog_Catalog_AttrDelete(intval(Router::GetParam(0)));
		header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/edit/".$this->oNode->getCatalog()."/");
	}
	
	protected function ActionDeactivateAttr(){
		$iId=Router::GetParam(0);
		if ($this->ComponentCatalog_Catalog_DeactivateAttr($iId)){ 
			$result['state']="success";
			$result['msg']=0;
		}
		else $result['state']="error";
		echo json_encode($result);
		exit;
	}
	protected function ActionActivateAttr(){
		$iId=Router::GetParam(0);
		if ($this->ComponentCatalog_Catalog_ActivateAttr($iId)){ 
			$result['state']="success";
			$result['msg']=1;
		}
		else $result['state']="error";
		echo json_encode($result);
		exit;
	}*/
	protected function ActionRemoveImage(){
		$iId=intval(Router::GetParam(0));
		$oCatalog=$this->ComponentCatalog_Catalog_GetCatalogById($iId);
		$this->Image_Delete($oCatalog->getImage());
		$oCatalog->setImage("");
		$this->ComponentCatalog_Catalog_Update($oCatalog);
		exit;
	}	
}	
