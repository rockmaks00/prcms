<?
class ComponentFeedback extends Component {
	public function Init(){		
		$this->SetDefaultAction('default');
		$this->oNode=Router::GetCurrentNode();
		$this->Template_SetPageTitle($this->oNode->getTitle());
		$this->Template_AddTitle($this->oNode->getTitle());
	}
	protected function RegisterActions() {
		$this->AddAction('default','ActionDefault');
		$this->AddAction('submit','ActionSubmit');
		$this->AddAction('success','ActionDefault');
		$this->AddAction('error','ActionDefault');
		$this->AddActionPreg('/^(page(\d+))?$/i','ActionDefault');
		// $this->AddActionPreg('/^about$/i','/^(page(\d+))?$/i','ActionDefault');
		// $this->AddActionPreg('/^bad$/i','/^(page(\d+))?$/i','ActionDefault');
		// $this->AddActionPreg('/^new$/i','/^(page(\d+))?$/i','ActionDefault');
	}	
	protected function ActionDefault(){
		$oMail = Engine::GetEntity("Mail");
		$oMail->getHeaders();

		$oFeedback = $this->ComponentFeedback_ModuleFeedback_GetFeedbackByNode( $this->oNode->getId() );
		$this->Template_Assign("oFeedback", $oFeedback);
		$bShowResults = $this->oNode->getParam("show_results");
		if( $bShowResults ) $aResults = $this->ComponentFeedback_ModuleFeedback_GetResultsByFeedback( $oFeedback->getId() );

		if( in_array(Router::GetAction(), array("success","error")) ) $this->Template_Assign("sMsg", $this->oNode->getParam( Router::GetAction() ));

		$this->Template_Assign("oFeedback", $oFeedback);
		$this->Template_Assign("aResults", $aResults);
		$this->Template_Assign("bShowResults", $bShowResults);
		$this->SetTemplate("default.tpl");
	}

	protected function ActionSubmit(){
		$oFeedback=$this->ComponentFeedback_ModuleFeedback_GetFeedbackByNode($this->oNode->getId());
		$sSecurity = getRequest("security");
		if( getRequest("feedback", null, "id") != $oFeedback->getId() || !empty($sSecurity) ){
			$bResult = "error";
		}else{
			$aFields=$this->ComponentFeedback_ModuleFeedback_GetFieldsByFeedback($oFeedback->getId());

			$oResult = Engine::GetEntity('ComponentFeedback_Feedback', null, 'Result');
			$oResult->setFeedback($oFeedback->getId());
			$oResult->setActive(0);
			$oResult=$this->ComponentFeedback_ModuleFeedback_AddResult($oResult);

			$aValues = array();
			foreach($aFields as $oField){
				if (getRequest($oField->getName())){
					$oValue = Engine::GetEntity('ComponentFeedback_Feedback', null, 'Value');
					$sValue = getRequest($oField->getName());
					if (is_array($sValue)) $sValue=implode(";", $sValue);
					$oValue->setValue($sValue);
					$oValue->setField($oField->getId());
					$oValue->setResult($oResult->getId());
					$oValue=$this->ComponentFeedback_ModuleFeedback_AddValue($oValue);
					$aValues[] = $oValue;
				}
			}
			
			$oMail = Engine::GetEntity("Mail");
			if( ( $sMail = $oResult->getMailAdress() ) ){
				$oMail->setTo($sMail);
				$oMail->setSubject("Мы получили Ваше письмо! (".Config::get("host").")");
				$oMail->setMessage('<h3>Спасибо за ваше обращение!</h3><p>Ваша заявка в разделе "'.$this->oNode->getTitle().'" принята! Мы постараемся дать ответ в кратчайшие сроки.</p> <p>Если Вы получили это письмо по ошибке, можете проигнорировать его. </p><br><br><h6>Это автоматически сгенерированное сообщение, не отвечайте на него: вполне возможно, что Ваше письмо не будет получено.</h6><hr><p>Администрация сайта '.Config::get("host").'</p>');
				$oMail->setFrom($oFeedback->getMails());
				$this->Mail_SendMail($oMail);
			}
			if( $oFeedback->getMails() ){
				$oNotification = Engine::GetEntity("Mail");
				$oNotification->setTo($oFeedback->getMails());
				$oNotification->setSubject("Новое сообщение с сайта ".Config::get("host"));
				$oNotification->setMessage('Новое сообщение в разделе <a href="'.Config::get("host").'admin/content/'.$this->oNode->getId().'/">"'.$this->oNode->getTitle().'"</a>!');
				if($sMail) $oNotification->setFrom($sMail);
				$this->Mail_SendMail($oNotification);
			}
			$bResult = "success";
		}

		if( Router::GetParam(0)=="ajax" ){
			$aResult = array('state'=>$bResult, 'msg'=>0);
			echo json_encode($aResult);
		}else{
			header("Location: ".$this->oNode->getFullUrl().$bResult."/");
		}
		exit;
	}
}