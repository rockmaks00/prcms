<?
class ComponentGallery_ModuleGallery_DbGallery extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_galleries") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_galleries` (
				`gallery_id` int(11) NOT NULL AUTO_INCREMENT,
				`gallery_title` varchar(250) NOT NULL,
				`gallery_description` text NOT NULL,
				`gallery_image` varchar(250) NOT NULL,
				`gallery_datetime` datetime NOT NULL,
				`gallery_active` int(11) NOT NULL DEFAULT '1',
				`gallery_node` int(11) NOT NULL,
				`gallery_sort` int(11) NOT NULL DEFAULT '500',
				PRIMARY KEY (`gallery_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_gallery_images") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_gallery_images` (
				`image_id` int(11) NOT NULL AUTO_INCREMENT,
				`image_title` varchar(250) NOT NULL,
				`image_description` text,
				`image_url` varchar(250) NOT NULL,
				`image_datetime` datetime NOT NULL,
				`image_width` int(11) DEFAULT NULL,
				`image_height` int(11) DEFAULT NULL,
				`image_gallery` int(11) NOT NULL,
				`image_sort` int(11) NOT NULL DEFAULT '500',
				PRIMARY KEY (`image_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
	}

	public function GetGalleryById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_galleries WHERE gallery_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentGallery_Gallery', $aRow);
		else return null;
	}
	
	public function GetGalleriesByNode($iNodeId) {
		$sql = "SELECT gallery_id FROM ".Config::Get("db.prefix")."com_galleries WHERE gallery_node=? ORDER BY gallery_sort, gallery_id";
		$aRows = $this->oDb->Select($sql, $iNodeId);
		return array_map(function($aRow){return $aRow["gallery_id"];}, $aRows);
	}
	
	public function Add($oGallery){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_galleries (
				gallery_title,
				gallery_description,
				gallery_image,
				gallery_datetime,
				gallery_active,
				gallery_node,
				gallery_sort
			) 
			VALUES(?, ?, ?, ?, ?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oGallery->getTitle(),
			$oGallery->getDescription(),
			$oGallery->getImage(),
			$oGallery->getDatetime(),
			$oGallery->getActive(),
			$oGallery->getNode(),
			$oGallery->getSort()
		);
	}
	
	public function Update($oGallery){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_galleries SET 
				gallery_title=?,
				gallery_datetime=?,
				gallery_description=?,
				gallery_image=?,
	            gallery_active=?,
	            gallery_node=?,
	            gallery_sort=?
			WHERE gallery_id=?
		";
		return $this->oDb->Query($sql, 
			$oGallery->getTitle(),
			$oGallery->getDatetime(),
			$oGallery->getDescription(),
			$oGallery->getImage(),
			$oGallery->getActive(),
			$oGallery->getNode(),
			$oGallery->getSort(),
			$oGallery->getId()
		);
	}
	
	public function Delete($iId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_galleries WHERE gallery_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
	
	
	
	
	/*----------------IMAGE BEGIN------------------*/
	
	public function GetImagesByGallery($iGalleryId) {
		$sql = "SELECT image_id FROM ".Config::Get("db.prefix")."com_gallery_images WHERE image_gallery=? ORDER BY image_sort ASC, image_datetime DESC, image_id ASC";
		$aIds = $this->oDb->Select($sql, $iGalleryId);
		return array_map(function($aId){return $aId["image_id"];}, $aIds);
	}
	
	public function GetImageById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_gallery_images WHERE image_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentGallery_Gallery', $aRow, 'Image');
		else return null;
	}
	
	public function AddImage($oImage){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_gallery_images (
				image_title,
				image_description,
				image_url,
				image_datetime,
				image_width,
				image_height,
				image_gallery,
				image_sort
			) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oImage->getTitle(),
			$oImage->getDescription(),
			$oImage->getUrl(),
			date("Y-m-d H:i:s"),
			$oImage->getWidth(),
			$oImage->getHeight(),
			$oImage->getGallery(),
			$oImage->getSort()
		);
	}
	
	public function UpdateImage($oImage){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_gallery_images SET 
				image_title=?,
				image_description=?,
				image_url=?,
				image_datetime=?,
				image_width=?,
				image_height=?,
				image_gallery=?,
				image_sort=?
			WHERE image_id=?
		";
		return $this->oDb->Query($sql, 
			$oImage->getTitle(),
			$oImage->getDescription(),
			$oImage->getUrl(),
			date("Y-m-d H:i:s"),
			$oImage->getWidth(),
			$oImage->getHeight(),
			$oImage->getGallery(),
			$oImage->getSort(),
			$oImage->getId()
		);
	}
	
	public function DeleteImage($iId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_gallery_images WHERE image_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}

	public function Search($sWord){
		$sWord = "%".$sWord."%";
		$sql = "SELECT gallery_id FROM ".Config::Get("db.prefix")."com_galleries WHERE (
			gallery_title LIKE ? OR
			gallery_description LIKE ?
			) AND gallery_active = 1 ";
		$aTmp = $this->oDb->Select($sql, $sWord, $sWord);
		$aRows["galleries"] = array_map(function($aVar){ return $aVar["gallery_id"]; }, $aTmp);

		$sql = "SELECT image_id FROM ".Config::Get("db.prefix")."com_gallery_images WHERE
			image_title LIKE ? OR
			image_description LIKE ?";
		$aTmp = $this->oDb->Select($sql, $sWord, $sWord);
		$aRows["images"] = array_map(function($aVar){ return $aVar["image_id"]; }, $aTmp);
		return $aRows;
	}
	/*----------------IMAGE END------------------*/
	

	
}	