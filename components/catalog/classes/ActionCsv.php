<?
	if (getRequest("sub")) {
		$aMas = array("item_count","item_group","item_price","item_title","item_node"
					,"item_active","item_image","item_sort");
			if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {
			$data = fgetcsv($handle, 0, ';');
			$aAttrs=$this->ComponentCatalog_Catalog_getCsvAttrsByNode($this->oNode->getId());
				while (($data = fgetcsv($handle, 0, ';')) !== FALSE) {

					$i=0;
					// mpr($aAttrs, 1);
					foreach ($data as $key => $value) {
						if ($aAttrs[$key][0]['csv_attr']<9000000){
							$aParams['dinamic'][]['csv_attr']=$aAttrs[$key][0]['csv_attr'];
							$aParams['dinamic'][]['value']=$value;
	  				} else {
	  					$aParams['static'][$aMas[$aAttrs[$key][0]['csv_attr']-9000000]]=$value;
	  				}
	  				$i++;
	 	 		}
	 	 		$this->ComponentCatalog_Catalog_UploadCsv($aParams,$this->oNode->getId());
			}
			fclose($handle);
		}
		if (!getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		else{ header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/CSV/"); exit; }
	} 
	else 
	{
		$aValues=$this->ComponentCatalog_Catalog_getCsvSemple($this->oNode->getId());
		$this->Template_Assign("aValues", $aValues);
		$aTitles=$this->ComponentCatalog_Catalog_getCsvAttr($this->oNode->getId());
		$this->Template_Assign("aTitles", $aTitles);
		$this->Template_Assign("sFormTitle", "Загрузить CSV");
		$this->SetTemplate("upload_csv.tpl");
}