<?
class ModuleCsv extends Module {
	public function Init() {}

	public function ParseFile($sFileName, $aSructure){
		if(($handle = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {
			$data = fgetcsv($handle, 0, ';');
		}
	}
}