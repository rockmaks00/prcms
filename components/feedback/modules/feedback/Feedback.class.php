<?
class ComponentFeedback_ModuleFeedback extends Module {
	protected $oDb;
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}
	
	public function GetFeedbackById($iId) {
		if (false === ($data = $this->Cache_Get("feedback_{$iId}"))) {
			$data=$this->oDb->GetFeedbackById($iId);
			$this->Cache_Set("feedback_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function GetFeedbackByNode($iNodeId) {
		if (false === ($data = $this->Cache_Get("feedback_node_{$iNodeId}"))) {
			$data=$this->oDb->GetFeedbackByNode($iNodeId);
			$this->Cache_Set("feedback_node_{$iNodeId}", $data, Config::Get("app.cache.expire"));
		}
		foreach($data as $i=>$iId){
			$data[$i]=$this->GetFeedbackById($iId['feedback_id']);
		}
		return $data[0];
	}
	
	public function Add(ComponentFeedback_ModuleFeedback_EntityFeedback $oFeedback) {
		$this->Cache_Delete("feedback_node_{$oFeedback->getNode()}");
		if ($iId=$this->oDb->Add($oFeedback)){
			$oFeedback->setId($iId);
		}
		return $oFeedback;
	}
	
	public function Update(ComponentFeedback_ModuleFeedback_EntityFeedback $oFeedback) {
		$this->Cache_Delete("feedback_{$oFeedback->getId()}");
		return $this->oDb->Update($oFeedback);
	}
	
	public function Delete($iId) {
		$oFeedback=$this->GetFeedbackById($iId);
		$this->Cache_Delete("feedback_{$oFeedback->getId()}");
		$this->Cache_Delete("feedback_node_{$oFeedback->getNode()}");
		return $this->oDb->Delete($iId);
	}
	
	
	
	/*----------------FIELD BEGIN------------------*/
	public function GetFieldsByFeedback($iFeedbackId) {
		if (false === ($data = $this->Cache_Get("feedback_fields_feedback_{$iFeedbackId}"))){
			$data=$this->oDb->GetFieldsByFeedback($iFeedbackId);
			while( list($iKey, $iId) = each($data) ){
				$data[$iKey] = $this->GetFieldById($iId);
			}
			$this->Cache_Set("feedback_fields_feedback_{$iFeedbackId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function GetFieldById($iId) {
		if (false === ($data = $this->Cache_Get("feedback_field_{$iId}"))) {
			$data=$this->oDb->GetFieldById($iId);
			$this->Cache_Set("feedback_field_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function AddField(ComponentFeedback_ModuleFeedback_EntityField $oField) {
		$this->Cache_Delete("feedback_fields_feedback_{$oField->getFeedback()}");
		if ($iId=$this->oDb->AddField($oField)){
			$oField->setId($iId);
		}
		return $oField;
	}
	
	public function UpdateField(ComponentFeedback_ModuleFeedback_EntityField $oField) {
		$this->Cache_Delete("feedback_field_{$oField->getId()}");
		$this->Cache_Delete("feedback_fields_feedback_{$oField->getFeedback()}");
		return $this->oDb->UpdateField($oField);
	}
	
	public function DeleteField($iId) {
		$oField=$this->GetFieldById($iId);
		$this->Cache_Delete("feedback_field_{$oField->getId()}");
		$this->Cache_Delete("feedback_fields_feedback_{$oField->getFeedback()}");
		return $this->oDb->DeleteField($iId);
	}
	public function ActivateField($iId) {
		$oField=$this->GetFieldById($iId);
		$oField->setActive(1);
		return $this->UpdateField($oField);
	}
	public function DeactivateField($iId) {
		$oField=$this->GetFieldById($iId);
		$oField->setActive(0);
		return $this->UpdateField($oField);
	}
	public function RequireField($iId) {
		$oField=$this->GetFieldById($iId);
		$oField->setRequired(1);
		return $this->UpdateField($oField);
	}
	public function UnrequireField($iId) {
		$oField=$this->GetFieldById($iId);
		$oField->setRequired(0);
		return $this->UpdateField($oField);
	}
	public function SortFields(ComponentFeedback_ModuleFeedback_EntityField $oField, $sAction) {
		$aFields = $this->GetFieldsByFeedback($oField->getFeedback());
		foreach($aFields as $i=>$oFieldEach){
			$oFieldEach->setSort(($i+1));
			if ($oFieldEach->getId() == $oField->getId()) $index=$i;
		}
		if ($sAction=="up"){
			if (isset($aFields[$index-1])){
				$tmp=$aFields[$index-1]->getSort();
				$aFields[$index-1]->setSort($aFields[$index]->getSort());
				$aFields[$index]->setSort($tmp);
			}
		}
		if ($sAction=="down"){
			if (isset($aFields[$index+1])){
				$tmp=$aFields[$index+1]->getSort();
				$aFields[$index+1]->setSort($aFields[$index]->getSort());
				$aFields[$index]->setSort($tmp);
			}
		}
		foreach($aFields as $oFieldEach){
			$this->UpdateField($oFieldEach);
		}
		return true;
	}	
	/*----------------FIELD END------------------*/
	
	
	
	/*----------------RESULT BEGIN------------------*/
	public function GetResultsByFeedback($iFeedbackId) {
		if (false === ($data = $this->Cache_Get("feedback_results_feedback_{$iFeedbackId}"))) {
			$data=$this->oDb->GetResultsByFeedback($iFeedbackId);
			while( list($iKey, $iId) = each($data) ){
				$data[$iKey]=$this->GetResultById($iId);
			}
			$this->Cache_Set("feedback_results_feedback_{$iFeedbackId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function GetResultById($iId) {
		if (1 or false === ($data = $this->Cache_Get("feedback_result_{$iId}"))){
			$data=$this->oDb->GetResultById($iId);
			$this->Cache_Set("feedback_result_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function AddResult(ComponentFeedback_ModuleFeedback_EntityResult $oResult) {
		$this->Cache_Delete("feedback_results_feedback_{$oResult->getFeedback()}");
		if ($iId=$this->oDb->AddResult($oResult)){
			$oResult->setId($iId);
		}
		return $oResult;
	}
	public function ActivateResult($iId) {
		$oField=$this->GetResultById($iId);
		$oField->setActive(1);
		return $this->UpdateResult($oField);
	}
	public function DeactivateResult($iId) {
		$oField=$this->GetResultById($iId);
		$oField->setActive(0);
		return $this->UpdateResult($oField);
	}
	public function UpdateResult(ComponentFeedback_ModuleFeedback_EntityResult $oResult) {
		$this->Cache_Delete("feedback_result_{$oResult->getId()}");
		$this->Cache_Delete("feedback_results_feedback_{$oResult->getFeedback()}");
		return $this->oDb->UpdateResult($oResult);
	}
	
	public function DeleteResult($iId) {
		$oResult = $this->GetResultById($iId);
		$aValues = $this->GetValuesByResult($iId);
		$aAnswers = $this->GetAnswersByResult($iId);
		foreach ($aValues as $oValue) $this->DeleteValue( $oValue->getId() );
		foreach ($aAnswers as $oAnswer) $this->DeleteAnswer( $oAnswer->getId() );
		$this->Cache_Delete("feedback_result_{$oResult->getId()}");
		$this->Cache_Delete("feedback_results_feedback_{$oResult->getFeedback()}");
		return $this->oDb->DeleteResult($iId);
	}	
	/*----------------RESULT END------------------*/
	


	/*----------------VALUE BEGIN------------------*/
	public function GetValuesByResult($iResultId) {
		if (false === ($data = $this->Cache_Get("feedback_values_result_{$iResultId}"))) {
			$data=$this->oDb->GetValuesByResult($iResultId);
			while( list($iKey, $iId) = each($data) ){
				$data[$iKey]=$this->GetValueById($iId);
			}
			$this->Cache_Set("feedback_values_result_{$iResultId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function GetValueById($iId) {
		if (false === ($data = $this->Cache_Get("feedback_value_{$iId}"))) {
			$data=$this->oDb->GetValueById($iId);
			$this->Cache_Set("feedback_value_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function AddValue(ComponentFeedback_ModuleFeedback_EntityValue $oValue) {
		$this->Cache_Delete("feedback_values_result_{$oValue->getResult()}");
		if ($iId=$this->oDb->AddValue($oValue)){
			$oValue->setId($iId);
		}
		return $oValue;
	}
	
	public function UpdateValue(ComponentFeedback_ModuleFeedback_EntityValue $oValue) {
		$this->Cache_Delete("feedback_value_{$oValue->getId()}");
		$this->Cache_Delete("feedback_values_result_{$oValue->getResult()}");
		return $this->oDb->UpdateValue($oValue);
	}
	
	public function DeleteValue($iId) {
		$oValue=$this->GetValueById($iId);
		$this->Cache_Delete("feedback_value_{$oValue->getId()}");
		$this->Cache_Delete("feedback_values_result_{$oValue->getResult()}");
		return $this->oDb->DeleteValue($iId);
	}
	/*----------------VALUE END------------------*/



	/*----------------ANSWER BEGIN------------------*/
	public function GetAnswersByResult($iResultId){
		if (false === ($data = $this->Cache_Get("feedback_answer_result_{$iResultId}"))) {
			$data=$this->oDb->GetAnswersByResult($iResultId);
			while( list($iKey, $iId) = each($data) ){
				$data[$iKey]=$this->GetAnswerById($iId);
			}
			$this->Cache_Set("feedback_answer_result_{$iResultId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetAnswerById($iId){
		if (false === ($data = $this->Cache_Get("feedback_answer_{$iId}"))) {
			$data=$this->oDb->GetAnswerById($iId);
			$this->Cache_Set("feedback_answer_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function AddAnswer(ComponentFeedback_ModuleFeedback_EntityAnswer $oAnswer){
		$this->Cache_Delete("feedback_answer_result_{$oAnswer->getResult()}");
		if ($iId = $this->oDb->AddAnswer($oAnswer)){
			$oAnswer->setId($iId);
		}
		return $oAnswer;
	}
	public function UpdateAnswer(ComponentFeedback_ModuleFeedback_EntityAnswer $oAnswer) {
		$this->Cache_Delete("feedback_answer_{$oAnswer->getId()}");
		$this->Cache_Delete("feedback_answer_result_{$oAnswer->getResult()}");
		return $this->oDb->UpdateAnswer($oAnswer);
	}
	public function DeleteAnswer($iId) {
		$oAnswer=$this->GetAnswerById($iId);
		$this->Cache_Delete("feedback_answer_{$oAnswer->getId()}");
		$this->Cache_Delete("feedback_answer_result_{$oAnswer->getResult()}");
		return $this->oDb->DeleteAnswer($iId);
	}
	public function ActivateAnswer($iId) {
		$oAnswer=$this->GetAnswerById($iId);
		if(!$oAnswer) return false;
		$oAnswer->setActive(1);
		return $this->UpdateAnswer($oAnswer);
	}
	public function DeactivateAnswer($iId) {
		$oAnswer=$this->GetAnswerById($iId);
		if(!$oAnswer) return false;
		$oAnswer->setActive(0);
		return $this->UpdateAnswer($oAnswer);
	}
	/*----------------ANSWER END------------------*/

	public function Search($aWords){
		return array();
	}
}