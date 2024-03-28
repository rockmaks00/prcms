<?
class ModuleDashboard_DbDashboard extends Db {
	public function GetDbSize() {
		$sql = "SHOW TABLE STATUS";
		$aRows=$this->oDb->Select($sql);
		
		$iResult=0;
		foreach ($aRows as $aRow) {
			$iResult+=$aRow['Data_length']+$aRow['Index_length'];
		}
		
		return round($iResult/10240)/100;
	}
}	