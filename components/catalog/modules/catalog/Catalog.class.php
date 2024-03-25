<?
class ComponentCatalog_ModuleCatalog extends Module {
	protected $oDb;
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}
	
	/*------------GROUPS BEGIN--------------*/
	public function GetGroupsByNode($iNodeId, $iParent=0) {
		if (false === ($data = $this->Cache_Get("catalog_groups_node_{$iNodeId}_{$iParent}"))) {
			$data=$this->oDb->GetGroupsByNode($iNodeId, $iParent);
			$this->Cache_Set("catalog_groups_node_{$iNodeId}_{$iParent}", $data, Config::Get("app.cache.expire"));
		}
		foreach($data as $i=>$iId){
			$data[$i]=$this->oDb->GetGroupById($iId['group_id']);
		}
		return $data;
	}
	
	public function GetGroupById($iId) {
		if (false === ($data = $this->Cache_Get("catalog_group_{$iId}"))) {
			$data=$this->oDb->GetGroupById($iId);
			$this->Cache_Set("catalog_group_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function GroupAdd(ComponentCatalog_ModuleCatalog_EntityGroup $oGroup) {
		$iParent = is_numeric($oGroup->getParent())?$oGroup->getParent():0;
		$this->Cache_Delete("catalog_groups_node_{$oGroup->getNode()}_{$iParent}");
		if ($iId=$this->oDb->GroupAdd($oGroup)){
			$oGroup->setId($iId);
		}
		return $oCatalog;
	}
	public function GroupUpdate(ComponentCatalog_ModuleCatalog_EntityGroup $oGroup) {
		$iParent = is_numeric($oGroup->getParent())?$oGroup->getParent():0;
		$this->Cache_Delete("catalog_groups_node_{$oGroup->getNode()}_{$iParent}");
		$this->Cache_Delete("catalog_group_{$oGroup->getId()}");
		return $this->oDb->GroupUpdate($oGroup);
	}
	
	public function GroupDelete($iId) {
		$oGroup=$this->GetGroupById($iId);
		$iParent = is_numeric($oGroup->getParent())?$oGroup->getParent():0;
		$this->Cache_Delete("catalog_groups_node_{$oGroup->getNode()}_{$iParent}");
		$this->Cache_Delete("catalog_group_{$oGroup->getId()}");
		return $this->oDb->GroupDelete($iId);
	}
	
	public function GroupActivate($iId) {
		$oGroup=$this->GetGroupById($iId);
		$oGroup->setActive(1);
		return $this->GroupUpdate($oGroup);
	}
	
	public function GroupDeactivate($iId) {
		$oGroup=$this->GetGroupById($iId);
		$oGroup->setActive(0); 
		return $this->GroupUpdate($oGroup);
	} 
	
	public function GetGroupParentIdById($iParentId) {
		if (false === ($data = $this->Cache_Get("catalog_{$iParentId}"))) {
			$data=$this->oDb->GetGroupParentIdById($iParentId);
			$this->Cache_Set("catalog_{$iParentId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	/*------------GROUPS END--------------*/
	
	
	
	/*------------ITEMS BEGIN--------------*/
	
	public function GetItemsByGroup($iId, $iNodeId=0){
		$oNode = Router::GetCurrentNode();
		if (!$iNodeId) $iNodeId=$oNode->getId();
		if (false === ($data = $this->Cache_Get("catalog_group_items_{$iId}_{$iNodeId}"))) {
			$data=$this->oDb->GetItemsByGroup($iId, $iNodeId);
			$this->Cache_Set("catalog_group_items_{$iId}_{$iNodeId}", $data, Config::Get("app.cache.expire"));
		}
		foreach($data as $i=>$iId){
			$data[$i]=$this->oDb->GetItemById($iId['item_id']);
		}
		return $data;
	}
	
	public function GetItemById($iId)
	{
		 if (false === ($data = $this->Cache_Get("catalog_item_{$iId}"))) {
			$data=$this->oDb->GetItemById($iId);
			$this->Cache_Set("catalog_item_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function ItemUpdate(ComponentCatalog_ModuleCatalog_EntityItem $oItem)  {
		$this->Cache_Delete("catalog_item_{$oItem->getId()}");
		$this->Cache_Delete("catalog_group_items_{$oItem->getGroup()}_{$oItem->getNode()}");
		return $this->oDb->ItemUpdate($oItem);
	}
	
	public function ItemAdd(ComponentCatalog_ModuleCatalog_EntityItem $oItem)
	{
		$this->Cache_Delete("catalog_group_items_{$oItem->getGroup()}_{$oItem->getNode()}");
		if ($iId=$this->oDb->ItemAdd($oItem)){
			$oItem->setId($iId);
		}
		return $oItem;
	}
	
	public function ItemActivate($iId) {
		$oGroup=$this->GetItemById($iId);
		$oGroup->setActive(1);
		return $this->ItemUpdate($oGroup);
	}
	
	public function ItemDeactivate($iId) {
		$oGroup=$this->GetItemById($iId);
		$oGroup->setActive(0); 
		return $this->ItemUpdate($oGroup);
	} 
	
	public function ItemDelete($iId) {
		$this->Cache_Delete("catalog_group_items_{$oItem->getGroup()}_{$oItem->getNode()}");
		return $this->oDb->ItemDelete($iId);
	}
	
	/*------------ITEMS END--------------*/
	
	
	
	
	
	
	
	
	/*------------ATTRIBUTES BEGIN--------------*/
	
	
		public function GetAttributesByNode($iNodeId){
			if (false === ($data = $this->Cache_Get("catalog_attributes_{$iNodeId}"))) {
				$data=$this->oDb->GetAttributesByNode($iNodeId);
				$this->Cache_Set("catalog_attributes_{$iNodeId}", $data, Config::Get("app.cache.expire"));
			}
			foreach($data as $i=>$iId){
				$data[$i]=$this->oDb->GetAttributeById($iId['attribute_id']);
			}
				
			return $data;
		}
	
		public function GetAttributeById($iAttributeId)
		{
			if (false === ($data = $this->Cache_Get("attribute_{$iAttributeId}"))) {
				$data=$this->oDb->GetAttributeById($iAttributeId);
				$this->Cache_Set("attribute_{$iAttributeId}", $data, Config::Get("app.cache.expire"));
			}
			return $data;
		}
		
		public function GetAttributeValue($iAttributeId, $iItemId)
		{
			//if (false === ($data = $this->Cache_Get("attribute_?_{$iAttributeId}"))) {
				$data=$this->oDb->GetAttributeValue($iAttributeId, $iItemId);
			//	$this->Cache_Set("attribute_{$iAttributeId}", $data, Config::Get("app.cache.expire"));
			//}
			return $data;
		}
		
		public function AttributeAdd($oAttribute)
		{
			if ($iId=$this->oDb->AttributeAdd($oAttribute)){
				$oAttribute->setId($iId);
			}
			return $oAttribute;
		}
		
		public function AttributeDelete($iId)
		{
			$this->Cache_Delete("attribute_ids");
			return $this->oDb->AttributeDelete($iId);
		}
		public function AttributeUpdate($oAttribute)  {
	
			if ($oAttribute) return $this->oDb->AttributeUpdate($oAttribute);   
		}
	
		public function AttributeActivate($iId)
		{
			$this->Cache_Delete("attribute_{$iId}");
			return $this->oDb->AttributeActivate($iId);
		}
		public function AttributeDeactivate($iId) {
			$this->Cache_Delete("attribute_{$iId}");
			return $this->oDb->AttributeDeactivate($iId);
		}
	
		public function ValueAdd($oValue){
			if ($iId=$this->oDb->ValueAdd($oValue)){
				$oValue->setId($iId);
			}
			return $oValue;
		}
		
		public function ValueUpdate($oValue)  {
			if ($oValue) return $this->oDb->ValueUpdate($oValue);   
		}
	/*------------ATTRIBUTES END--------------*/
	
	
	
	
	/*------------CART BEGIN--------------
		public function CartAdd($iItemId, $iCount){
			return $this->oDb->CartAdd($iItemId, $this->GetCartId(), $iCount);
		}
		
		public function GetCartId()  {
			if (!$_COOKIE['CID']){
				$CID = md5(time().rand());
				setcookie("CID", $CID, time()+60*60*24*30, "/");
				$_COOKIE['CID'] = $CID;
			}
			return $_COOKIE['CID'];
		}
	/*------------CART END--------------*/
	
	public function GetAttrsByItem($iId)
	{
		if($data=$this->oDb->GetAttrsByItem($iId))
		{
			foreach ($data as $i=>$oItem)
			{
				$data[$i]=$this->oDb->GetAttrItemById($oItem['attritem_id']);
				if ($data[$i]['attr_type']==5)
				{
					$data[$i]['param']=$this->oDb->GetParamDivision($data[$i]['attr_param']);
				}
			}
		}
		return $data;
	}
	public function GetNewsDivision()
	{
		if ($data=$this->oDb->GetNewsDivision())
		{
			foreach($data as $i=>$oItem)
			{
				$data[$i]=$this->oDb->GetNewsById($oItem['node_id']);
			}
		}
		return $data;
	}
	public function GetCatalog() {
		if (false === ($data = $this->Cache_Get("catalog_ids"))) {
			$data=$this->oDb->GetCatalog();
			$this->Cache_Set("catalog_ids", $data, Config::Get("app.cache.expire"));
		}
		foreach($data as $i=>$iId){
			$data[$i]=$this->oDb->GetCatalogById($iId['catalog_id']);
		}
		return $data;
	}
	public function GetCatalogsByParent($iCatalogId)
	{
		if (false === ($data = $this->Cache_Get("catalog_{$iCatalogId}"))) {
			$data=$this->oDb->GetCatalogsByParent($iCatalogId);
			$this->Cache_Set("catalog_{$iCatalogId}", $data, Config::Get("app.cache.expire"));
		}
		foreach($data as $i=>$iId){
			$data[$i]=$this->oDb->GetParentById($iId);
		}
		return $data;
	}
	public function GetCatalogs()
	{
		if ($data=$this->oDb->GetCatalogs())
			return $data; else return null;
	}
	public function GetParentById($iParentId)
	{
		if (false === ($data = $this->Cache_Get("catalog_{$iParentId}"))) {
			$data=$this->oDb->GetParentById($iParentId['catalog_id']);
			$this->Cache_Set("catalog_{$iParentId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function AddCatalogStruct($aStruct,$iCatalogId)
	{

		if (isset($aStruct))
			foreach($aStruct as $oItem)
			{
				$oItem->setCatalog($iCatalogId);
				$this->oDb->AddAttr($oItem);
			}
			return;
	}
	public function GetAttrByCatalog($iId)
	{
		if (false === ($data = $this->Cache_Get("attr_{$iId}"))) {
			$data=$this->oDb->GetAttrByCatalog($iId);
			$this->Cache_Set("attr_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		foreach($data as $i=>$iId){
			if ($iId['attr_type']!=5)
			{
				$data[$i]=$this->oDb->GetAttrById($iId['attr_id']);
			}else 
				{
					$data[$i]['param']=$this->oDb->GetParamDivision($iId['attr_param']);
				}   
		}
		return $data;
	}
	public function GetCatalogById($iId) {
		if (false === ($data = $this->Cache_Get("catalog_{$iId}"))) {
			$data=$this->oDb->GetCatalogById($iId);
			$this->Cache_Set("catalog_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function AddParent(ComponentCatalog_ModuleCatalog_EntityCatalog $oCatalog) {
		$this->Cache_Delete("catalog_ids");
		$this->Cache_Delete("catalog_node_{$oCatalog->getNode()}");
		if ($iId=$this->oDb->AddParent($oCatalog)){
			$oCatalog->setId($iId);
		}
		return $oCatalog;
	}
	public function CatalogAttrDelete($iId)
	{
		$this->Cache_Delete("item_ids");
		return $this->oDb->CatalogAttrDelete($iId);
	}  
	public function getCsvAttr($iId)
	{
		if (false === ($data = $this->Cache_Get("catalog_{$iId}"))) {
			$aAllattrs=$this->oDb->getCsvAttr($iId);
			$aCsvattrs=$this->getCsvAttrsByNode($iId);
			$aAllattrs=array_merge($aAllattrs,$this->getStatick());
			foreach($aCsvattrs as $key=>$value)
			{
				foreach($aAllattrs as $keyGR=>$valueGR)
				{
					if (gettype($valueGR)=="array"){
						if ($valueGR['attribute_id']==$value[0]['csv_attr']){
							$aCurrent[]=$valueGR;
						}
					} else {
						if ($this-> getStatick($valueGR,"search")==$value[0]['csv_attr']){
							$aCurrent[]=$valueGR;
						}
					} 
				}
			}
			foreach($aAllattrs as $key=>$value)
			{
			$bBool=false;
				foreach($aCsvattrs as $keyGR=>$valueGR)
				{
					if (gettype($value)=="array"){
						if ($value['attribute_id']==$valueGR[0]['csv_attr']){
							$bBool=true;
						}
					} else {
						if ($this-> getStatick($value,"search")==$valueGR[0]['csv_attr']){
							$bBool=true;
						}
					} 
				}
				if ($bBool==false)
				$result[]=$value;
			}
			$result['current']=$aCurrent;
			$this->Cache_Set("catalog_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $result;
	}
	public function getStatick($iId,$sParam)
	{
		$aMas = array("'item_count'","'item_group'","'item_price'","'item_title'","'item_node'"
					,"'item_active'","'item_image'","'item_sort'");
		if (isset($sParam)){
			switch($sParam)
			{
				case "search":{
					return array_search($iId,$aMas)+9000000; break;
				}
			}
		} else {
			return $aMas;
		}
	}
	public function getCsvSemple($iId)
	{
		if (false === ($data = $this->Cache_Get("semple_{'$iId'}"))) {
			$data=$this->oDb->getCsvSemple($iId);
			$this->Cache_Set("semple_{'$iId'}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function setCsvTemplate($iId,$sType,$iNodeId)
	{
		if (false === ($data = $this->Cache_Get("csvtemplate_{$iId}"))) {
			$data=$this->oDb->setCsvTemplate($iId,$sType,$iNodeId);
			$this->Cache_Set("csvtemplate_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function ClearCsv($iId)
	{
		return $this->oDb->ClearCsv($iId);
	}
	public function getCsvAttrsByNode($iId)
	{
		$data=$this->oDb->getCsvAttrsByNode($iId);
		foreach ($data as $value) {
			$result[]=$this->oDb->getCsvAttrById($value['csv_id']);
		}
		return $result;
	}
	public function GetItemsByNode($iNodeId=0){
		$oNode = Router::GetCurrentNode();
		if (!$iNodeId) $iNodeId=$oNode->getId();
		if (false === ($data = $this->Cache_Get("catalog_group_items_{$iNodeId}"))) {
			$data=$this->oDb->GetItemsByNode($iNodeId);
			$this->Cache_Set("catalog_group_items_{$iNodeId}", $data, Config::Get("app.cache.expire"));
		}
		foreach($data as $i=>$iId){
			$data[$i]=$this->oDb->GetItemById($iId['item_id']);
		}
		return $data;
	}
	public function UploadCsv($aMass,$iNode,$iGroupId)
	{
		$oItem = Engine::GetEntity('ComponentCatalog_Catalog', null, 'Item');

		foreach ($aMass['static'] as $key => $value) {
			switch ($key) {
				case 'item_count':
					{$oItem->setCount($value);}
					break;
				case 'item_price':
					{$oItem->setPrice($value);}
					break;
					case 'item_title':
					{$oItem->setTitle($value);}
					break;
					case 'item_sort':
					{$oItem->setSort($value);}
					break;
					case 'item_node':
					{$oItem->setNode($iNode);}
					break;
					case 'item_active':
					{$oItem->setActive($value);}
					break;
					case 'item_group':
					{$oItem->setGroup($value);}
					break;
					case 'item_image':
					{$oItem->setImage($value);}
					break;
				default:
					# code...
					break;
			}
		}

		$this->oDb->ItemAdd($oItem);
		$aItems=$this->GetItemsByNode($iNode);
		$oAttribute = Engine::GetEntity('ComponentCatalog_Catalog', null, 'Attribute');
		$oAttribute->setOwner($aItems[count($aItems)-1]['item_id']);
		if (isset($aMass['dinamic'])){
			foreach ($aMass['dinamic'] as $key => $value) {
				if (isset($value['csv_attr']))
					$oAttribute->setAttribute($value['csv_attr']);
				if (isset($value['value'])){
					$oAttribute->setValue($value['value']);
					}
				}	
			$this->oDb->ValueAdd($oAttribute);
		}
	}
	
	public function Search($aWords){
		return array();
	}
}