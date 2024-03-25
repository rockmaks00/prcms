<?
class ComponentFeedback_ModuleFeedback_DbFeedback extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_feedback") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_feedback` (
				`feedback_id` int(11) NOT NULL AUTO_INCREMENT,
				`feedback_title` varchar(250) NOT NULL,
				`feedback_mails` varchar(250) NOT NULL,
				`feedback_node` int(11) NOT NULL,
				PRIMARY KEY (`feedback_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			return $this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_feedback_answers") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_feedback_answers` (
				`answer_id` int(11) NOT NULL AUTO_INCREMENT,
				`answer_author` varchar(250) NOT NULL,
				`answer_text` text NOT NULL,
				`answer_datetime` datetime NOT NULL,
				`answer_result` int(11) NOT NULL,
				`answer_active` int(1) NOT NULL,
				`answer_sent` int(1) NOT NULL,
				PRIMARY KEY (`answer_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			return $this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_feedback_fields") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_feedback_fields` (
				`field_id` int(11) NOT NULL AUTO_INCREMENT,
				`field_title` varchar(250) NOT NULL,
				`field_name` varchar(250) NOT NULL,
				`field_type` enum('text','textarea','mail','phone','file','checkbox','radio','image','button','label','select') NOT NULL,
				`field_value` text NOT NULL,
				`field_required` int(11) NOT NULL DEFAULT '0',
				`field_feedback` int(11) NOT NULL,
				`field_sort` int(11) NOT NULL DEFAULT '500',
				`field_active` int(11) NOT NULL DEFAULT '1',
				PRIMARY KEY (`field_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			return $this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_feedback_results") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_feedback_results` (
				`result_id` int(11) NOT NULL AUTO_INCREMENT,
				`result_feedback` int(11) NOT NULL,
				`result_datetime` datetime NOT NULL,
				`result_active` int(11) NOT NULL,
				PRIMARY KEY (`result_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			return $this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_feedback_values") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_feedback_values` (
				`value_id` int(11) NOT NULL AUTO_INCREMENT,
				`value_value` text NOT NULL,
				`value_field` int(11) NOT NULL,
				`value_result` int(11) NOT NULL,
				PRIMARY KEY (`value_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			return $this->oDb->Query($sql);
		}
	}


	public function GetFeedbackById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_feedback WHERE feedback_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentFeedback_Feedback', $aRow);
		else return null;
	}
	
	public function GetFeedbackByNode($iNodeId) {
		$sql = "SELECT feedback_id FROM ".Config::Get("db.prefix")."com_feedback WHERE feedback_node=?";
		return $this->oDb->Select($sql, $iNodeId);
	}
	
	public function Add(ComponentFeedback_ModuleFeedback_EntityFeedback $oFeedback){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_feedback (
				feedback_title,
				feedback_node,
				feedback_mails		
			) 
			VALUES(?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oFeedback->getTitle(),
			$oFeedback->getNode(),
			$oFeedback->getMails()
		);
	}
	
	public function Update(ComponentFeedback_ModuleFeedback_EntityFeedback $oFeedback){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_feedback SET 
				feedback_title=?,
	            feedback_node=?,
	            feedback_mails=?
			WHERE feedback_id=?
		";
		return $this->oDb->Query($sql, 
			$oFeedback->getTitle(),
			$oFeedback->getNode(),
			$oFeedback->getMails(),
			$oFeedback->getId()
		);
	}
	
	public function Delete($iId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_feedback WHERE feedback_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
	
	
	
	
	
	/*----------------FIELD BEGIN------------------*/
	
	public function GetFieldsByFeedback($iFeedbackId) {
		$sql = "SELECT field_id FROM ".Config::Get("db.prefix")."com_feedback_fields WHERE field_feedback=? ORDER BY field_sort, field_id";
		$data = $this->oDb->Select($sql, $iFeedbackId);
		return array_map(function($aField){ return $aField["field_id"]; }, $data);
	}
	
	public function GetFieldById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_feedback_fields WHERE field_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentFeedback_Feedback', $aRow, 'Field');
		else return null;
	}
	
	public function AddField(ComponentFeedback_ModuleFeedback_EntityField $oField){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_feedback_fields (
				field_title,
				field_name,
				field_type,
				field_value,
				field_required,
				field_feedback,
				field_sort,
				field_active	
			) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oField->getTitle(),
			$oField->getName(),
			$oField->getType(),
			$oField->getValue(),
			$oField->getRequired(),
			$oField->getFeedback(),
			$oField->getSort(),
			$oField->getActive()
		);
	}
	
	public function UpdateField(ComponentFeedback_ModuleFeedback_EntityField $oField){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_feedback_fields SET 
				field_title=?,
				field_name=?,
	            field_type=?,
	            field_value=?,
	            field_required=?,
	            field_feedback=?,
	            field_active=?,
	            field_sort=?
			WHERE field_id=?
		";
		return $this->oDb->Query($sql, 
			$oField->getTitle(),
			$oField->getName(),
			$oField->getType(),
			$oField->getValue(),
			$oField->getRequired(),
			$oField->getFeedback(),
			$oField->getActive(),
			$oField->getSort(),
			$oField->getId()
		);
	}
	
	public function DeleteField($iId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_feedback_fields WHERE field_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
	/*----------------FIELD END------------------*/
	
	/*----------------RESULT BEGIN------------------*/
	
	public function GetResultsByFeedback($iFeedbackId) {
		$sql = "SELECT result_id FROM ".Config::Get("db.prefix")."com_feedback_results WHERE result_feedback=? ORDER BY result_datetime DESC, result_id DESC";
		$data = $this->oDb->Select($sql, $iFeedbackId);
		return array_map( function($aResult){ return $aResult["result_id"]; }, $data );
	}
	
	public function GetResultById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_feedback_results WHERE result_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentFeedback_Feedback', $aRow, 'Result');
		else return null;
	}
	
	public function AddResult($oResult){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_feedback_results (
				result_feedback,
				result_datetime,
				result_active	
			) 
			VALUES(?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oResult->getFeedback(),
			date("Y-m-d H:i:s"),
			$oResult->getActive()
		);
	}
	
	public function UpdateResult($oResult){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_feedback_results SET 
				result_feedback=?,
				result_datetime=?,
	            result_active=?
			WHERE result_id=?
		";
		return $this->oDb->Query($sql, 
			$oResult->getFeedback(),
			$oResult->getDatetime(),
			$oResult->getActive(),
			$oResult->getId()
		);
	}
	
	public function DeleteResult($iId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_feedback_results WHERE result_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
	/*----------------RESULT END------------------*/
	
	
	/*----------------VALUE BEGIN------------------*/
	
	public function GetValuesByResult($iResultId) {
		$sql = "SELECT value_id FROM ".Config::Get("db.prefix")."com_feedback_values WHERE value_result=?";
		$data = $this->oDb->Select($sql, $iResultId);
		return array_map(function($aValue){ return $aValue["value_id"]; }, $data);
	}
	
	public function GetValueById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_feedback_values WHERE value_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentFeedback_Feedback', $aRow, 'Value');
		else return null;
	}
	
	public function AddValue($oValue){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_feedback_values (
				value_value,
				value_field,
				value_result	
			) 
			VALUES(?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oValue->getValue(),
			$oValue->getField(),
			$oValue->getResult()
		);
	}
	
	public function UpdateValue($oValue){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_feedback_values SET 
				value_value=?,
				value_field=?,
				value_result=?
			WHERE value_id=?
		";
		return $this->oDb->Query($sql, 
			$oValue->getValue(),
			$oValue->getField(),
			$oValue->getResult(),
			$oValue->getId()
		);
	}
	
	public function DeleteValue($iId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_feedback_values WHERE value_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
	/*----------------VALUE END------------------*/
	
	/*----------------ANSWER BEGIN------------------*/
	public function AddAnswer($oAnswer){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_feedback_answers (
				answer_author,
				answer_text,
				answer_datetime,
				answer_result,
				answer_active,
				answer_sent
			) 
			VALUES(?, ?, ?, ?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oAnswer->getAuthor(),
			$oAnswer->getText(),
			$oAnswer->getDatetime(),
			$oAnswer->getResult(),
			$oAnswer->getActive(),
			$oAnswer->getSent()
		);
	}
	public function UpdateAnswer($oAnswer){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_feedback_answers SET
				answer_author=?,
				answer_text=?,
				answer_datetime=?,
				answer_result=?,
				answer_active=?,
				answer_sent=?
			WHERE answer_id=?
		";
		return $this->oDb->Query($sql, 
			$oAnswer->getAuthor(),
			$oAnswer->getText(),
			$oAnswer->getDatetime(),
			$oAnswer->getResult(),
			$oAnswer->getActive(),
			$oAnswer->getSent(),
			$oAnswer->getId()
		);
	}
	public function GetAnswersByResult($iId){
		$sql = "SELECT answer_id FROM ".Config::Get("db.prefix")."com_feedback_answers WHERE answer_result=?";
		$aRows=$this->oDb->Select($sql, $iId);
		return array_map(function($aRow){return $aRow["answer_id"];}, $aRows);
	}
	public function GetAnswerById($iId){
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_feedback_answers WHERE answer_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentFeedback_Feedback', $aRow, 'Answer');
		else return null;
	}
	public function DeleteAnswer($iId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_feedback_answers WHERE answer_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
	/*----------------ANSWER END------------------*/
}	