<?
class ComponentCatalog_ModuleCatalog_DbCatalog extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_catalog_groups") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_catalog_groups` (
				`group_id` int(11) NOT NULL AUTO_INCREMENT,
				`group_title` varchar(150) NOT NULL,
				`group_node` int(11) NOT NULL,
				`group_active` int(11) NOT NULL,
				`group_image` varchar(255) DEFAULT NULL,
				`group_parent` int(11) NOT NULL,
				`group_desc` text NOT NULL,
				`group_sort` int(11) NOT NULL DEFAULT '500',
				PRIMARY KEY (`group_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_catalog_items") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_catalog_items` (
				`item_description` text,
				`item_id` int(11) NOT NULL AUTO_INCREMENT,
				`item_count` int(11) NOT NULL DEFAULT '1',
				`item_group` int(11) NOT NULL,
				`item_price` float DEFAULT NULL,
				`item_title` varchar(150) NOT NULL,
				`item_node` int(11) NOT NULL,
				`item_active` int(11) NOT NULL DEFAULT '1',
				`item_image` varchar(255) DEFAULT NULL,
				`item_sort` int(11) NOT NULL DEFAULT '500',
				PRIMARY KEY (`item_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_catalog_attributes") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_catalog_attributes` (
				`attribute_id` int(11) NOT NULL AUTO_INCREMENT,
				`attribute_name` varchar(150) NOT NULL,
				`attribute_title` varchar(150) NOT NULL,
				`attribute_type` enum('text','textarea','select','checkbox','file') NOT NULL,
				`attribute_default` text NOT NULL,
				`attribute_active` int(11) NOT NULL,
				`attribute_sort` int(11) NOT NULL DEFAULT '500',
				`attribute_node` int(11) NOT NULL,
				`attribute_csvactive` int(11) NOT NULL DEFAULT '0',
				`attribute_csvtype` int(11) NOT NULL,
				PRIMARY KEY (`attribute_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_catalog_attributes_values") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_catalog_attributes_values` (
				`value_id` int(11) NOT NULL AUTO_INCREMENT,
				`value_owner` int(11) NOT NULL,
				`value_attribute` int(11) NOT NULL,
				`value_value` text NOT NULL,
				PRIMARY KEY (`value_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		/*if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_catalog_cart") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_catalog_cart` (
				`cart_id` int(11) NOT NULL AUTO_INCREMENT,
				`cart_item` int(11) NOT NULL,
				`cart_sid` varchar(50) NOT NULL,
				`cart_count` int(11) NOT NULL,
				PRIMARY KEY (`cart_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}*/
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_csv") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_csv` (
				`csv_id` int(11) NOT NULL AUTO_INCREMENT,
				`csv_active` int(11) NOT NULL,
				`csv_node` int(11) NOT NULL,
				`csv_attr` int(11) NOT NULL,
				`csv_type` varchar(50) NOT NULL,
				`csv_sort` int(11) NOT NULL,
				PRIMARY KEY (`csv_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
	}
	/*------------GROUPS BEGIN--------------*/
	public function GetGroupById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_catalog_groups WHERE group_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentCatalog_Catalog', $aRow, 'Group');
		else return null;
	}
	public function GetGroupsByNode($iNodeId, $iParent) {
		$sql = "SELECT group_id FROM ".Config::Get("db.prefix")."com_catalog_groups WHERE group_node=? AND group_parent=? ORDER BY group_sort, group_id";
		return $this->oDb->Select($sql, $iNodeId, $iParent);
	}
	public function GroupAdd($oGroup){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_catalog_groups (
				group_title,
				group_node,
				group_desc,
				group_parent,
				group_active
			)
			VALUES(?,?,?,?,?)
		";
		return $this->oDb->Query($sql, 
			$oGroup->getTitle(),
			$oGroup->getNode(),
			$oGroup->getDesc(),
			$oGroup->getParent(),
			$oGroup->getActive()
		);
	}
	public function GroupUpdate($oGroup){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_catalog_groups SET 
				group_title=?,
	            group_active=?,
				group_desc=?,
				group_parent=?,
	            group_image=?
			WHERE group_id=?
		";
		return $this->oDb->Query($sql, 
			$oGroup->getTitle(),
			$oGroup->getActive(),
			$oGroup->getDesc(),
			$oGroup->getParent(),
			$oGroup->getImage(),
			$oGroup->getId()
		);
	}
	public function GroupDelete($iGroupId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_catalog_items WHERE item_group=?";
		if ($this->oDb->Query($sql, $iGroupId)) 
		{
			$sql = "DELETE FROM ".Config::Get("db.prefix")."com_catalog_groups WHERE group_id=?";
			if ($this->oDb->Query($sql, $iGroupId)) return true;
		}
		else return false;
	}


	public function GetGroupParentIdById($iId) {
		$sql="SELECT `group_parent` FROM ".Config::Get("db.prefix")."com_catalog_groups where group_id=?";
		$aGroupId=$this->oDb->Select($sql, $iId);
		$iGroupId=$aGroupId[0]["group_parent"];
		if ($iGroupId) return $iGroupId;
		else return null;
	}
	/*------------GROUPS END--------------*/
	
	
	
	/*------------ITEMS BEGIN--------------*/
	public function GetItemsByGroup($iId, $iNodeId)
	{
		$sql="SELECT item_id FROM ".Config::Get("db.prefix")."com_catalog_items WHERE item_group=? AND item_node=? ORDER BY item_sort, item_id";
		return $this->oDb->Select($sql,$iId,$iNodeId);
	}
	public function GetItemById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_catalog_items WHERE item_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentCatalog_Catalog', $aRow, "Item");
		else return null;
	}
	public function ItemAdd($oItem)
	{
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_catalog_items (
			item_price,
			item_title,
			item_group,
			item_node,
			item_active,
			item_count,
			item_sort,
			item_image
			) 
			VALUES(?,?,?,?,?,?,?,?)
		";
		return $this->oDb->Query($sql, 
			$oItem->getPrice(),
			$oItem->getTitle(),
			$oItem->getGroup(),
			$oItem->getNode(),
			$oItem->getActive(),
			$oItem->getCount(),
			$oItem->getSort(),
			$oItem->getImage()
		);
	}
	public function ItemUpdate($oItem)
	{
		$sql = "UPDATE ".Config::Get("db.prefix")."com_catalog_items SET 
			item_count=?,
			item_price=?,
			item_title=?,
			item_group=?,
			item_active=?, 
			item_sort=?, 
			item_image=?
			WHERE item_id=?
		";
		return $this->oDb->Query($sql, 
			$oItem->getCount(),
			$oItem->getPrice(),
			$oItem->getTitle(),
			$oItem->getGroup(),
			$oItem->getActive(),
			$oItem->getSort(),
			$oItem->getImage(),
			$oItem->getId()
		);
	}
	public function ItemDelete($iItemId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_catalog_items WHERE item_id=?";
		return $this->oDb->Query($sql, $iItemId);
	}
	/*------------ITEMS END--------------*/
	
	
	
	
	
	/*------------ATTRIBUTES BEGIN--------------*/
	public function GetAttributesByNode($iNodeId)
	{
		$sql="SELECT attribute_id FROM ".Config::Get("db.prefix")."com_catalog_attributes WHERE attribute_node=? ORDER BY attribute_sort";
		return $this->oDb->Select($sql,$iNodeId);
	}
	
	public function GetAttributeById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_catalog_attributes WHERE attribute_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentCatalog_Catalog', $aRow, "Attribute");
		else return null;
	}
	
	public function GetAttributeValue($iAttributeId, $iItemId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_catalog_attributes_values WHERE value_attribute=? AND value_owner=?";
		$aRow=$this->oDb->SelectRow($sql, $iAttributeId, $iItemId);
		if ($aRow) return Engine::GetEntity('ComponentCatalog_Catalog', $aRow, "Value");
		else return null;
	}
	public function AttributeAdd($oAttribute)
	{
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_catalog_attributes (
				attribute_title,
				attribute_name,
				attribute_type,
				attribute_default,
				attribute_active,
				attribute_sort,
				attribute_node
			) 
			VALUES (?,?,?,?,?,?,?)
		";
		return $this->oDb->Query($sql, 
			$oAttribute->getTitle(),
			$oAttribute->getName(),
			$oAttribute->getType(),
			$oAttribute->getDefault(),
			$oAttribute->getActive(),
			$oAttribute->getSort(),
			$oAttribute->getNode()
		);
	}
	
	public function AttributeUpdate($oAttribute){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_catalog_attributes SET 
				attribute_title=?,
	            attribute_name=?,
	            attribute_type=?,
	            attribute_default=?,
	            attribute_active=?,
	            attribute_sort=?
			WHERE attribute_id=?
		";
		return $this->oDb->Query($sql, 
			$oAttribute->getTitle(),
			$oAttribute->getName(),
			$oAttribute->getType(),
			$oAttribute->getDefault(),
			$oAttribute->getActive(),
			$oAttribute->getSort(),
			$oAttribute->getId()
		);
	}
	
	public function AttributeDelete($iId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_catalog_attributes WHERE attribute_id=?";
		return $this->oDb->Query($sql, $iId);
	}
	
	public function ValueAdd($oValue)
	{
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_catalog_attributes_values (
				value_owner,
				value_attribute,
				value_value
			) 
			VALUES (?,?,?)
		";
		return $this->oDb->Query($sql, 
			$oValue->getOwner(),
			$oValue->getAttribute(),
			$oValue->getValue()
		);
	}
	
	public function ValueUpdate($oValue){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_catalog_attributes_values SET 
				value_owner=?,
				value_attribute=?,
				value_value=?
			WHERE value_id=?
		";
		return $this->oDb->Query($sql, 
			$oValue->getOwner(),
			$oValue->getAttribute(),
			$oValue->getValue(),
			$oValue->getId()
		);
	}
	/*------------ATTRIBUTES END--------------*/
	
	
	
	
	/*------------CART BEGIN--------------
    	public function CartAdd($iItemId, $sid, $iCount)
	{
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_catalog_cart (
				cart_item,
				cart_sid,
				cart_count
			) 
			VALUES (?,?,?)
		";
		return $this->oDb->Query($sql, 
			$iItemId,
			$sid,
			$iCount
		);
	}
    /*------------CART END--------------*/


	//------доработка начало-----//

	public function getCsvAttr($iId)
	{
		$sql="SELECT attribute_title,attribute_type,attribute_id FROM ".Config::Get("db.prefix")."com_catalog_attributes where
		 attribute_node=".$iId;
		 return $this->oDb->Select($sql);

	}
	public function getCsvSemple($iId)
	{	
		$sql="SELECT min(item_id) FROM ".Config::Get("db.prefix")."com_catalog_items where
		item_node=?";
		$min_id=$this->oDb->Query($sql,$iId);
		$data=$this->GetItemById($min_id);
		return $data;
	}
	public function ClearCsv($iId)
	{
		$sql="DELETE FROM ".Config::Get("db.prefix")."com_csv where csv_node=?";
		return $this->oDb->Query($sql,$iId);
	}
	public function setCsvTemplate($iId,$sType,$iNodeId)
	{
		$sql="INSERT INTO ".Config::Get("db.prefix")."com_csv (csv_attr,csv_type,csv_node,csv_active)
		VALUES(?,?,?,1)";
		return $this->oDb->Query($sql, 
			$iId,
			$sType,
			$iNodeId
		);
	}
	public function getCsvAttrsByNode($iId)
	{
		$sql="SELECT csv_id FROM ".Config::Get("db.prefix")."com_csv where csv_node=?";
		return $this->oDb->Select($sql,$iId);
	}
	public function getCsvAttrById($iId)
	{
		$sql="SELECT * FROM ".Config::Get("db.prefix")."com_csv where csv_id=?";
		return $this->oDb->Select($sql,$iId);	
	}
	public function getItemsByNode($iNodeId)
	{
		$sql="SELECT item_id FROM ".Config::Get("db.prefix")."com_catalog_items where item_node=?";
		return $this->oDb->Select($sql,$iNodeId);
	}
	//------доработка конец-----//
	public function GetParamDivision($iId)
	{
		$sql = "SELECT news_id,news_title FROM ".Config::Get("db.prefix")."com_news where news_node=?";
		return $this->oDb->Select($sql,$iId);
	}
	public function GetCatalog() {
		$sql = "SELECT catalog_id FROM ".Config::Get("db.prefix")."com_catalog ORDER BY catalog_id DESC";
		return $this->oDb->Select($sql);
	}
	public function GetCatalogsByParent($iParentId)
	{
		$sql="SELECT catalog_id FROM ".Config::Get("db.prefix")."com_catalog WHERE catalog_parent=? ORDER BY catalog_id DESC";
		return $this->oDb->Select($sql, $iParentId);
	}
	public function GetParentById($iId)
	{
		$sql="SELECT * FROM ".Config::Get("db.prefix")."com_catalog where catalog_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId['catalog_id']);
		if ($aRow) return Engine::GetEntity('ComponentCatalog_Catalog', $aRow);
		else return null;
	}
	
	public function GetCatalogs()
	{
		$sql="SELECT distinct(attr_catalog),catalog_title FROM ".Config::Get("db.prefix")."com_catalog,".Config::Get("db.prefix")."com_catalog_attr 
		where catalog_id=attr_catalog";
		$aRows=$this->oDb->Select($sql);
		return $aRows;
	}
	public function GetAttrByCatalog($iId)
	{
		$sql="select attr_id from ".Config::Get("db.prefix")."com_catalog_attr where attr_catalog=?";
		return $this->oDb->Select($sql,$iId);
	}
	public function GetAttrById($iId)
	{
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_catalog_attr WHERE attr_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentCatalog_Catalog', $aRow,"Attr");
		else return null;
	}
	public function GetStructByCatalog($iId)
	{
		$sql = "SELECT attr_id FROM ".Config::Get("db.prefix")."com_catalog_attr WHERE attr_catalog=?";
		return $this->oDb->Select($sql, $iId);
	}
	public function GetAttrsByItem($iId)
	{
		$sql="SELECT attritem_id FROM ".Config::Get("db.prefix")."com_catalog_items_attr WHERE attritem_item=?";
		return $this->oDb->Select($sql, $iId);
	}
	public function GetAttrItemById($iId)
	{
		$sql="SELECT attr_type, attr_title, attritem_id,attr_param,  attritem_item,  attritem_attr,  attritem_value ,  
		attr_type FROM ".Config::Get("db.prefix")."com_catalog_attr, ".Config::Get("db.prefix")."com_catalog_items_attr
		WHERE  attritem_attr =  attr_id AND  attritem_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return $aRow;
		else return null;
	}
	
	public function CatalogAttrDelete($iId)
	{
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_catalog_items_attr WHERE attritem_attr=?";
		if ($this->oDb->Query($sql, $iId)) 
			{
				$sql="DELETE FROM ".Config::Get("db.prefix")."com_catalog_attr WHERE attr_id=?";
				if ($this->oDb->Query($sql,$iId)) return true;
			} 
			else return false;
	}
		public function ItemAttrUpdate($oAttr)
		{
			$sql = "UPDATE ".Config::Get("db.prefix")."com_catalog_items_attr SET 
				attritem_value=?
				WHERE attritem_id=?
			";
			return $this->oDb->Query($sql, 
			$oAttr['attritem_value'],
			$oAttr['attritem_id']
			);
		}
	public function AddItemAttr($oAttr)
	{
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_catalog_items_attr (
			attritem_item,
			attritem_attr,
			attritem_value
			)
			VALUES(?,?,?)
		";
		return $this->oDb->Query($sql, 
			$oAttr->getType(),
			$oAttr->getId(),
			$oAttr->getTitle()
		);
	}
	public function AddCatalogStruct($iId)
	{
		$sql = "SELECT attr_id FROM ".Config::Get("db.prefix")."com_catalog_attr WHERE attr_catalog=?";
		return $this->Select($sql,$iId);
	}
	
	public function AddParent($oCatalog){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_catalog (
				catalog_title,
				catalog_node,
				catalog_desc,
				catalog_active,
				catalog_parent
			)
			VALUES(?,?,?,?,?)
		";
		return $this->oDb->Query($sql, 
			$oCatalog->getTitle(),
			$oCatalog->getNode(),
			$oCatalog->getDesc(),
			$oCatalog->getActive(),
			$oCatalog->getParent()
		);
	}
	
	
}	