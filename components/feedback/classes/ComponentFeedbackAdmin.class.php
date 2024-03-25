<?
class ComponentFeedbackAdmin extends Component {
	protected $oNode=null;
	protected $sAction=null;
	protected $aParams=array();
	protected $aLang=array();
	
	public function Init(){		
		$this->SetDefaultAction('default'); // Устанавливает экшн по умолчанию
		$this->oNode=Router::GetCurrentNode(); // Текущий редактируемый раздел
		$this->sAction=Router::GetActionAdmin(); // Текущий экшн
		$this->aParams=Router::getParams(); // Массив параметров
		$this->sTemplatePath = $this->Template_GetHost()."components/admin/templates/default/"; // Путь до шаблона админки
		$this->Template_Assign("aLang", $this->aLang);
	}
	
	protected function RegisterActions() {
		$this->AddAction('default','ActionDefault');
		$this->AddAction('update','ActionUpdate');
		
		$this->AddAction('result_edit','ActionResultEdit');
		$this->AddAction('result_update','ActionResultUpdate');
		$this->AddAction('result_delete','ActionDeleteResult');
		$this->AddAction('result_deleteall','ActionDeleteAllResults');
		$this->AddAction('result_activate','ActionResultActivate');
		
		$this->AddAction('field_add','ActionFieldForm');
		$this->AddAction('field_edit','ActionFieldForm');
		$this->AddAction('field_update','ActionFieldUpdate');
		$this->AddAction('field_required','ActionFieldRequired');
		$this->AddAction('field_activate','ActionFieldActivate');
		$this->AddAction('field_delete','ActionDeleteField');
		$this->AddAction('field_deleteall','ActionDeleteAllFields');
		$this->AddAction('field_sort','ActionSortFields');

		$this->AddAction('answers','ActionAnswers');
		$this->AddAction('answer_add','ActionAnswerFrom');
		$this->AddAction('answer_edit','ActionAnswerFrom');
		$this->AddAction('answer_update','ActionAnswerUpdate');
		$this->AddAction('answer_activate','ActionAnswerActivate');
	}
	

	/*----------------MAIN BEGIN------------------*/
	protected function ActionDefault() {
		if(!$this->AccessCheck("R")) return;
		$oFeedback=$this->ComponentFeedback_Feedback_GetFeedbackByNode($this->oNode->getId());
		if (!$oFeedback){
			$oFeedback=Engine::GetEntity('ComponentFeedback_Feedback');
			$oFeedback->setMails("admin@vprioritete.ru");
			$oFeedback->setTitle($this->oNode->getTitle());
			$oFeedback->setNode($this->oNode->getId());
			$oFeedback=$this->ComponentFeedback_Feedback_Add($oFeedback);
		}

		$aFields=$this->ComponentFeedback_Feedback_GetFieldsByFeedback($oFeedback->getId());
		$aResults=$this->ComponentFeedback_Feedback_GetResultsByFeedback($oFeedback->getId());
		
		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		$this->Template_AddJs($this->sTemplatePath."assets/ckeditor/ckeditor.js");			
		$this->Template_AddJs($this->Template_GetHost()."components/feedback/templates/admin/feedback.js");

		$this->Template_Assign("sFormAction", "update");
		$this->Template_Assign("oFeedback", $oFeedback);
		$this->Template_Assign("aFields", $aFields);
		$this->Template_Assign("aResults", $aResults);
		$this->Template_Assign("sFormTitle", $this->oNode->getTitle());
		$this->SetTemplate("feedback_list.tpl");
	}
	
	protected function ActionUpdate(){
		if(!$this->AccessCheck("V")) return;
		$iId = getRequest("id", null, "id");
		if($iId) $oFeedback = $this->ComponentFeedback_Feedback_GetFeedbackById($iId);
		else $oFeedback = Engine::GetEntity("ComponentFeedback_Feedback");

		$oFeedback->setMails( getRequest("mails") );
		$oFeedback->setTitle( $this->oNode->getTitle() );
		$oFeedback->setNode(  $this->oNode->getId() );

		if ($oFeedback->getId()) $this->ComponentFeedback_Feedback_Update($oFeedback);
		else $this->ComponentFeedback_Feedback_Add($oFeedback);

		$oPage = $oFeedback->getPageObject();
		$oPage->setNode( $this->oNode->getId() );
		$oPage->setBody( getRequest("text") );

		if( $oPage->getId() ) $this->ComponentPage_Page_Update( $oPage );
		else $this->ComponentPage_Page_Add( $oPage );

		if (getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		else header("Location: ".Config::Get("host")."admin/nodes/");
	}
	/*----------------MAIN END------------------*/
	


	/*----------------RESULT BEGIN------------------*/
	protected function ActionResultEdit(){
		if(!$this->AccessCheck("V")) return;
		$this->Template_Assign("sFormTitle", "Редактирование результата");
		$this->Template_Assign("sFormAction", "result_update");

		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		$this->Template_AddJs($this->sTemplatePath."assets/ckeditor/ckeditor.js");

		$iId = Router::GetParam(0);
		$oResult = $this->ComponentFeedback_Feedback_GetResultById( $iId );
		if( !$oResult ) return $this->NotFound();
		$aFields = $this->ComponentFeedback_Feedback_GetFieldsByFeedback( $oResult->getFeedback() );
		$this->Template_Assign("oResult", $oResult);
		$this->Template_Assign("aFields", $aFields);
		$this->SetTemplate("result_form.tpl");
	}
	protected function ActionResultUpdate(){
		if(!$this->AccessCheck("V")) return;
		$iId = getRequest("id");
		$oResult = $this->ComponentFeedback_Feedback_GetResultById( $iId );
		if( !$oResult ) return $this->NotFound();
		$aResultValues = $oResult->getValues();
		$aFields = $this->ComponentFeedback_Feedback_GetFieldsByFeedback( $oResult->getFeedback() );

		foreach($aFields as $oField){
			$sValue = getRequest( $oField->getName() );
			// var_dump($sValue);
			// echo $oField->getName()."<br>\r\n";
			if( !$sValue ) continue;
			if( !( $oValue = $aResultValues[$oField->getName()]["value"] ) ){
				$oValue = Engine::GetEntity("ComponentFeedback_Feedback",null,"Value");
				$oValue->setResult( $oResult->getId() );
				$oValue->setField(  $oField->getId()  );
			}
			if( is_array($sValue) ) $sValue = implode(";",$sValue);
			$oValue->setValue($sValue);

			if( $oValue->getId() )  $this->ComponentFeedback_Feedback_UpdateValue( $oValue );
			else $this->ComponentFeedback_Feedback_AddValue( $oValue );
		//mpr($oValue);
		}
//die('1');

		if (!getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		else header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/result_edit/".$oResult->getId()."/");
	}
	protected function ActionResultActivate(){
		if(!$this->AccessCheck("V")) return;
		$sMode = Router::GetParam(0);
		$iId = intval(Router::GetParam(1));

		if($sMode=="activate"){
			if( $this->ComponentFeedback_Feedback_ActivateResult($iId) ){
				$result['state']="success";
				$result['msg']=1;
			}else $result['state']="error";
		}elseif($sMode=="deactivate"){
			if ($this->ComponentFeedback_Feedback_DeactivateResult($iId) ){ 
				$result['state']="success";
				$result['msg']=0;
			}else $result['state']="error";
		}else{
			$result['state']="error";	
		}
		echo json_encode($result);
		exit;
	}
	protected function ActionDeleteResult(){
		if(!$this->AccessCheck("W")) return;
		$this->ComponentFeedback_Feedback_DeleteResult(intval(Router::GetParam(0)));
		header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
	}
	public function ActionDeleteAllResults(){
		if(!$this->AccessCheck("R")) return;
		if( $aFields = explode(',', Router::GetParam(0)) ){
			foreach ($aFields as $iId){
				$this->ComponentFeedback_Feedback_DeleteResult( intval($iId) );
			}
		}
		$result['state']="success";
		$result['msg']=0;
		echo json_encode($result);
		exit;
	}
	/*----------------RESULT BEGIN------------------*/



	/*----------------FIELD BEGIN------------------*/
	protected function ActionFieldForm(){
		if(!$this->AccessCheck("V")) return;
		$this->Template_Assign("sFormTitle", ( $this->sAction == "field_add" ? "Добавление" : "Редактирование" )." поля");
		$this->Template_Assign("sFormAction", "field_update");

		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		$this->Template_AddJs($this->sTemplatePath."assets/ckeditor/ckeditor.js");

		$iId = Router::GetParam(0);
		if( !$iId ) return $this->NotFound();
		if( $this->sAction == "field_add" ){
			$oField = Engine::GetEntity("ComponentFeedback_Feedback", null, "Field");
			$oField->setFeedback($iId);
			$oField->setActive(1);
			$oField->setSort(500);
		}else{
			$oField = $this->ComponentFeedback_Feedback_GetFieldById( $iId );
		}
		
		$this->Template_Assign("oField", $oField);
		$this->SetTemplate("field_form.tpl");
	}

	protected function ActionFieldUpdate() {
		if(!$this->AccessCheck("V")) return;
		$iId = getRequest("id");
		if( $iId ) $oField = $this->ComponentFeedback_Feedback_GetFieldById($iId);
		else $oField = Engine::GetEntity("ComponentFeedback_Feedback", null, "Field");

		$oField->setTitle(		getRequest('title')	);
		$oField->setName(		getRequest('name')	);
		$oField->setType(		getRequest('type')	);
		$oField->setValue(		getRequest('value')	);
		$oField->setRequired(	getRequest('required'));
		$oField->setFeedback(	getRequest('parent'));
		$oField->setActive(		getRequest('active'));
		$oField->setSort(		getRequest('sort')	);

		if ($oField->getId()) $this->ComponentFeedback_Feedback_UpdateField($oField);
		else $oField=$this->ComponentFeedback_Feedback_AddField($oField);

		if (!getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/#portlet_tab2");
		else header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/field_edit/".$oField->getId()."/");
	}
	
	public function ActionFieldRequired(){
		if(!$this->AccessCheck("V")) return;
		$sMode = Router::GetParam(0);
		$iId = intval(Router::GetParam(1));

		if($sMode=="activate"){
			if( $this->ComponentFeedback_Feedback_RequireField($iId) ){
				$result['state']="success";
				$result['msg']=1;
			}else $result['state']="error";
		}elseif($sMode=="deactivate"){
			if ($this->ComponentFeedback_Feedback_UnrequireField($iId) ){ 
				$result['state']="success";
				$result['msg']=0;
			}else $result['state']="error";
		}else{
			$result['state']="error";	
		}
		echo json_encode($result);
		exit;
	}
	public function ActionFieldActivate(){
		if(!$this->AccessCheck("V")) return;
		$sMode = Router::GetParam(0);
		$iId = intval(Router::GetParam(1));

		if($sMode=="activate"){
			if( $this->ComponentFeedback_Feedback_ActivateField($iId) ){
				$result['state']="success";
				$result['msg']=1;
			}else $result['state']="error";
		}elseif($sMode=="deactivate"){
			if ($this->ComponentFeedback_Feedback_DeactivateField($iId) ){ 
				$result['state']="success";
				$result['msg']=0;
			}else $result['state']="error";
		}else{
			$result['state']="error";	
		}
		echo json_encode($result);
		exit;
	}
	
	protected function ActionDeleteField(){
		if(!$this->AccessCheck("W")) return;
		$this->ComponentFeedback_Feedback_DeleteField(intval(Router::GetParam(0)));
		header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/#portlet_tab2");
	}
	public function ActionDeleteAllFields(){
		if( !$this->AccessCheck("W") ) return;
		if( $aFields = explode(',', Router::GetParam(0)) ){
			foreach ($aFields as $iId){
				$this->ComponentFeedback_Feedback_DeleteField( intval($iId) );
			}
		}
		$result['state']="success";
		$result['msg']=0;
		echo json_encode($result);
		exit;
	}
	public function ActionSortFields(){
		if( !$this->AccessCheck("V") ) return;
		$oField 	= $this->ComponentFeedback_Feedback_GetFieldById( intval(Router::GetParam(0)) );
		$sSortAction= Router::GetParam(1);
		
		$this->ComponentFeedback_Feedback_SortFields($oField, $sSortAction);
		
		$oFeedback	= $this->ComponentFeedback_Feedback_GetFeedbackById( $oField->getFeedback() );
		$aFields	= $this->ComponentFeedback_Feedback_GetFieldsByFeedback($oFeedback->getId());

		$this->Template_Assign("oFeedback", $oFeedback);
		$this->Template_Assign("aFields", $aFields);

		$this->SetTemplate("feedback_fields_list_portlet.tpl");
	}
	/*----------------FIELD END------------------*/



	/*----------------ANSWER BEGIN------------------*/
	public function ActionAnswers(){
		if(!$this->AccessCheck("R")) return;
		$this->Template_Assign("sFormTitle", "Ответы");
		$iId = Router::GetParam(0);
		if( !is_numeric($iId) ) return $this->NotFound();

		$oResult = $this->ComponentFeedback_Feedback_GetResultById($iId);
		$aAnswers = $this->ComponentFeedback_Feedback_GetAnswersByResult($iId);

		$this->Template_Assign("oResult", $oResult);
		$this->Template_Assign("aAnswers", $aAnswers);

		$this->SetTemplate("answers_list.tpl");
	}
	public function ActionAnswerFrom(){
		if(!$this->AccessCheck("V")) return;
		$this->Template_Assign("sFormTitle", "Написать ответ");
		$this->Template_Assign("sFormAction", "answer_update");

		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/ckeditor/ckeditor.js");

		$iId = Router::GetParam(0);
		if( !is_numeric($iId) ) return $this->NotFound();
		if( $this->sAction == "answer_add" ){
			$oResult = $this->ComponentFeedback_Feedback_GetResultById( $iId );
			if( !$oResult ) return $this->NotFound();
			$oAnswer = Engine::GetEntity("ComponentFeedback_Feedback", null, "Answer");
			$oAnswer->setAuthor( $this->User_GetUserCurrent()->getName() );
			$oAnswer->setResult( $oResult->getId() );
			$oAnswer->setActive(1);
		}else{
			$oAnswer = $this->ComponentFeedback_Feedback_GetAnswerById( $iId );
			if( !$oAnswer ) return $this->NotFound();
			$oResult = $this->ComponentFeedback_Feedback_GetResultById( $oAnswer->getResult() );
		}
		
		$this->Template_Assign("oAnswer", $oAnswer);
		$this->Template_Assign("oResult", $oResult);
		$this->SetTemplate("answer_form.tpl");
	}
	public function ActionAnswerUpdate(){
		if(!$this->AccessCheck("V")) return;
		$oResult = $this->ComponentFeedback_Feedback_GetResultById( getRequest("result", null, "id") );
		if( !$oResult ) return $this->AccessDenied();
		if( $iId = getRequest("id", null, "id") ){
			$oAnswer = $this->ComponentFeedback_Feedback_GetAnswerById($iId);
		}else{
			$oAnswer = Engine::GetEntity("ComponentFeedback_Feedback", null, "Answer");
			$oAnswer->setDatetime( date("Y-m-d H:i:s") );
			$oAnswer->setResult( getRequest("result") );
			$oAnswer->setSent(0);
		}
		
		$oAnswer->setAuthor( getRequest("author") );
		$oAnswer->setText(   getRequest("text") );
		$oAnswer->setActive( getRequest("active") );

		if( ( $sEmail = $oResult->getMailAdress() ) && strip_tags(trim($oAnswer->getText())) && !$oAnswer->getSent() ){
			$oFeedback = $this->ComponentFeedback_Feedback_GetFeedbackById( $oResult->getFeedback() );
			$oMail = Engine::GetEntity("Mail");
			$oMail->setTo($sEmail);
			$oMail->setSubject("Ответ администратора сайта ".Config::Get("host")." в разделе \"".$this->oNode->getTitle()."\"" );
			$oMail->setMessage( $oAnswer->getText() );
			$oMail->setFrom( $oFeedback->getMails() );

			$this->Mail_SendMail($oMail);
			$oAnswer->setSent(1);
		}

		if( $oAnswer->getId() ){
			$this->ComponentFeedback_Feedback_UpdateAnswer($oAnswer);
		}else{
			$this->ComponentFeedback_Feedback_AddAnswer($oAnswer);
		}

		header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/answers/".$oResult->getId()."/");
	}
	public function ActionAnswerActivate(){
		if(!$this->AccessCheck("V")) return;
		$sMode = Router::GetParam(0);
		$iId = intval(Router::GetParam(1));

		if($sMode=="activate"){
			if( $this->ComponentFeedback_Feedback_ActivateAnswer($iId) ){
				$result['state']="success";
				$result['msg']=1;
			}else $result['state']="error";
		}elseif($sMode=="deactivate"){
			if ($this->ComponentFeedback_Feedback_DeactivateAnswer($iId) ){ 
				$result['state']="success";
				$result['msg']=0;
			}else $result['state']="error";
		}else{
			$result['state']="error";	
		}
		echo json_encode($result);
		exit;
	}
	/*----------------ANSWER END------------------*/
}