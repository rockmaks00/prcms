<?
class ModuleMenu_DbMenu extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."menu") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."menu` (
				`menu_id` int(11) NOT NULL AUTO_INCREMENT,
				`menu_title` varchar(250) NOT NULL,
				`menu_name` varchar(250) NOT NULL,
				`menu_active` int(11) NOT NULL DEFAULT '1',
				PRIMARY KEY (`menu_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."menu_items") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."menu_items` (
				`item_id` int(11) NOT NULL AUTO_INCREMENT,
				`item_title` varchar(250) NOT NULL DEFAULT '',
				`item_node` int(11) NOT NULL DEFAULT '0',
				`item_url` varchar(250) NOT NULL DEFAULT '',
				`item_parent` int(11) NOT NULL DEFAULT '0',
				`item_menu` int(11) NOT NULL DEFAULT '0',
				`item_img` varchar(250) DEFAULT '',
				`item_active` int(11) NOT NULL DEFAULT '1',
				`item_sort` int(11) NOT NULL DEFAULT '500',
				PRIMARY KEY (`item_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
	}
	public function Add($oMenu){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."menu (
				menu_title,
	            menu_name,
	            menu_active
			) 
			VALUES(?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oMenu->getTitle(),
			$oMenu->getName(),
			$oMenu->getActive()
		);
	}
	public function Update($oMenu){
		$sql = "UPDATE ".Config::Get("db.prefix")."menu SET 
				menu_title=?,
	            menu_name=?,
	            menu_active=?			
			WHERE menu_id=?
		";
		return $this->oDb->Query($sql, 
			$oMenu->getTitle(),
			$oMenu->getName(),
			$oMenu->getActive(),
			$oMenu->getId()
		);
	}
	public function GetMenu() {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."menu ORDER BY menu_id";
		$aResult = array();
		$aRows=$this->oDb->Select($sql);
		foreach ($aRows as $aRow) {
			$aResult[]=Engine::GetEntity('Menu',$aRow);
		}
		return $aResult;
	}
	public function GetMenuByName($sName) {
		$sql = "SELECT menu_id FROM ".Config::Get("db.prefix")."menu WHERE menu_name = ?";
		$aRow=$this->oDb->SelectRow($sql, $sName);
		return $aRow["menu_id"];
	}
	public function GetMenuById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."menu WHERE menu_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('Menu', $aRow);
		else return null;
	}
	
	public function Activate($iMenuId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."menu SET menu_active=1 WHERE menu_id=?";
		if ($this->oDb->Query($sql, $iMenuId)) return true;
		else return false;
	}
	
	public function Deactivate($iMenuId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."menu SET menu_active=0 WHERE menu_id=?";
		if ($this->oDb->Query($sql, $iMenuId)) return true;
		else return false;
	}
	
	public function Delete($iMenuId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."menu WHERE menu_id=?";
		if ($this->oDb->Query($sql, $iMenuId)) return true;
		else return false;
	}
/*ITEMS*/
	public function AddItem($oItem){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."menu_items (
				item_title,
				item_node,
				item_url,
				item_parent,
				item_menu,
				item_img,
				item_active,
				item_sort
			) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oItem->getTitle(),
			$oItem->getNode(),
			$oItem->getUrl(),
			$oItem->getParent(),
			$oItem->getMenu(),
			$oItem->getImg(),
			$oItem->getActive(),
			$oItem->getSort()
		);
	}
	public function UpdateItem($oItem){
		$sql = "UPDATE ".Config::Get("db.prefix")."menu_items SET 
				item_title=?,
				item_node=?,
				item_url=?,
				item_parent=?,
				item_menu=?,
				item_img=?,
				item_active=?,
				item_sort=?			
			WHERE item_id=?
		";
		return $this->oDb->Query($sql, 
			$oItem->getTitle(),
			$oItem->getNode(),
			$oItem->getUrl(),
			$oItem->getParent(),
			$oItem->getMenu(),
			$oItem->getImg(),
			$oItem->getActive(),
			$oItem->getSort(),
			$oItem->getId()
		);
	}
	public function GetItemList($iMenuId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."menu_items WHERE item_menu=? ORDER BY item_sort, item_id";
		$aResult = array();
		$aRows=$this->oDb->Select($sql, $iMenuId);
		foreach ($aRows as $aRow) {
			$aResult[]=Engine::GetEntity('Menu',$aRow, "Item");
		}
		return $aResult;
	}
	public function GetItemsByParent($iParentId, $sMenuId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."menu_items WHERE item_parent=? AND item_menu=? ORDER BY item_sort, item_id";
		$aResult = array();
		$aRows=$this->oDb->Select($sql, $iParentId, $sMenuId);
		foreach ($aRows as $aRow) {
			$aResult[]=Engine::GetEntity('menu',$aRow, "Item");
		}
		return $aResult;
	}
	public function GetItemById($iItemId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."menu_items WHERE item_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iItemId);
		if ($aRow) return Engine::GetEntity('Menu', $aRow, "Item");
		else return null;
	}
	
	public function ActivateItem($iItemId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."menu_items SET item_active=1 WHERE item_id=?";
		if ($this->oDb->Query($sql, $iItemId)) return true;
		else return false;
	}
	
	public function DeactivateItem($iItemId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."menu_items SET item_active=0 WHERE item_id=?";
		if ( $this->oDb->Query($sql, $iItemId) ) return true;
		else return false;
	}
	
	public function DeleteItem($iItemId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."menu_items WHERE item_id=?";
		if ( $this->oDb->Query($sql, $iItemId) ) return true;
		else return false;
	}
}	