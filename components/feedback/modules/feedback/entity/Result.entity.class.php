<?
class ComponentFeedback_ModuleFeedback_EntityResult extends Entity {
    public function getAnswers(){
		if( !isset($this->_aData['result_answers']) ){
			$aAnswers = Engine::GetInstance()->ComponentFeedback_Feedback_GetAnswersByResult($this->getId());
			$this->setAnswers($aAnswers);
		}
		return $this->_aData['result_answers'];
	}

	public function getMailAdress(){
		$aMails = array();
		foreach($this->getValues() as $aValue){
			if( $aValue["field"]->getType()=="mail" && ($sMail = validateVar( $aValue["value"]->getValue(), "mail" ) ) ) $aMails[]=$sMail;
		}
		return implode(",", $aMails);
	}

	public function getValues(){
		if( !isset($this->_aData['result_values']) ){
			$aValues=Engine::GetInstance()->ComponentFeedback_Feedback_GetValuesByResult($this->getId());
			$aResults=array();
			foreach($aValues as $i=>$oValue){
				$oField=Engine::GetInstance()->ComponentFeedback_Feedback_GetFieldById($oValue->getField());
				//$aResults[$oField ? $oField->getName() : "undefined_".$i] = $oValue->getValue();
				$mKey = $oField ? $oField->getName() : $i;
				$aResults[$mKey]["field"] = $oField ? $oField : Engine::GetEntity("ComponentFeedback_Feedback", null, "Field");
				$aResults[$mKey]["value"] = $oValue;
			}
			uasort($aResults, function($a,$b){
				if($a["field"]->getSort() < $b["field"]->getSort()) return -1;
				return 1; 
			});
			$this->setValues($aResults);
		}
		return $this->_aData['result_values'];
	}

}	