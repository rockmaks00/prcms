<?
abstract class Component extends EngineObject {
	
	protected $aParams=array();
	// protected $aParamsCurrent=array();
	protected $oEngine=null;
	protected $sDefaultAction=null;
	protected $aActions=array();
	protected $sCurrentAction=array();
	protected $oComponentAdmin=null;
	
	public function __construct(){
		$this->oEngine=Engine::getInstance();
	}
	
	abstract protected function RegisterActions();
		
	public function SetDefaultAction($sAction) {
		$this->sDefaultAction=$sAction;
	}
	
	public function GetDefaultAction() {
		return $this->sDefaultAction;
	}
	
	protected function AddAction($sActionName,$sActionFunction) {
		$this->AddActionPreg("/^{$sActionName}$/i",$sActionFunction);
	}
	
	protected function AddActionPreg() {		
		$iCountArgs=func_num_args();
		if ($iCountArgs<2) exit;
		$aAction=array();
		$aAction['function'] = func_get_arg($iCountArgs-1);
		if (!method_exists($this, $aAction['function'])) {			
			die("Method of the action does not exist: ".$aAction['function']);
		}
		$aAction['preg']=func_get_arg(0);
		$aAction['params_preg']=array();
		for ($i=1;$i<$iCountArgs-1;$i++) {
			$aAction['params_preg'][]=func_get_arg($i);
		}
		$this->aActions[]=$aAction;
	}
	
	public function ExecAction($sClass=null) {		
		$oNode=Router::getCurrentNode();
		$sAction=Router::GetAction();

		$this->sCurrentAction=Router::GetAction();
		
		if ($this->sCurrentAction==null) {
			$this->sCurrentAction=$this->GetDefaultAction();
			Router::SetAction($this->sCurrentAction);
		}
		foreach ($this->aActions as $aAction) {
			
			if (preg_match($aAction['preg'],$this->sCurrentAction,$aMatch)) {
				$this->aParamsEventMatch['action']=$aMatch;
				$this->aParamsEventMatch['params']=array();
				foreach ($aEvent['params_preg'] as $iKey => $sParamPreg) {
					if (preg_match($sParamPreg,$this->GetParam($iKey,''),$aMatch)) {
						$this->aParamsEventMatch['params'][$iKey]=$aMatch;
					} else {
						continue 2;
					}
				}
				// $result = call_user_func_array(array( $this,  $aAction['function'] ));
				eval('$result=$this->'.$aAction['function'].'();');
				return $result;
			}
		}
		return $this->NotFound();
	}	
	
	public function Exec($sClass=null){
		if (preg_match("/^Component(\w+)Admin$/i", $sClass,$aMatch)){
			$this->aActions=array();
			Router::SetAction(Router::GetActionAdmin());
			Router::SetNodeUrl("admin/content/".Router::GetParam(0)."/");
			$oNode=$this->oEngine->Node_GetNodeById(Router::GetParam(0));
			Router::SetCurrentNode($oNode);
			$aParams=Router::GetParams();
			array_shift($aParams);
			array_shift($aParams);	
			Router::SetParams($aParams);
		}else{
			$oNode=Router::GetCurrentNode();
			if(!$oNode->getActive()) return $this->NotFound();
			//$this->GetParamsCurrent($oNode);
		}
		$this->Init();
		$this->RegisterActions();
		$this->ExecAction($sClass);
	}
	
	protected function NotFound() {
		$this->Template_ClearAllAssign();
		header("HTTP/1.0 404 Not Found");
		$this->Template_SetCss("/components/admin/templates/default/assets/fonts/font.css");
		$this->Template_AddCss("/templates/errors/assets/styles.css");
		$this->Template_SetJs("/templates/errors/assets/custom.js");
		Router::SetCurrentTemplate("templates/errors/404.tpl");
	}
	protected function AccessDenied() {
		$this->Template_ClearAllAssign();
		header("HTTP/1.0 403 forbidden");
		$this->Template_SetCss("/components/admin/templates/default/assets/fonts/font.css");
		$this->Template_AddCss("/templates/errors/assets/styles.css");
		$this->Template_SetJs("/templates/errors/assets/custom.js");
		Router::SetCurrentTemplate("templates/errors/403.tpl");
	}
	
	protected function AccessCheck($sAccessNeeded="W") {
		if( get_class($this) == "ComponentAdmin" ){
			if ( $this->sCurrentAction == "nodes" ) {
				$iNodeId = Router::GetParam(1);
				if( is_numeric($iNodeId) ){
					$sUserAccess = $this->User_GetUserCurrent()->getAccess( "node".$iNodeId );
					if( $sUserAccess === false ) $sUserAccess = $this->User_GetUserCurrent()->getAccess("nodes");
				}else{
					$sUserAccess = $this->User_GetUserCurrent()->getAccess("nodes");
					if( $sUserAccess == "D" and count( $this->User_GetNodesAvailable( $sAccessNeeded ) ) ){
						$sUserAccess = "R";
					}
				}
			}elseif( $this->sCurrentAction == "content" ){
				$sUserAccess = "W";
			}else{
				$sUserAccess = $this->User_GetUserCurrent()->getAccess( $this->sCurrentAction );
			}
		}else{
			$iNodeId = $this->oNode->getId();
			$sUserAccess = $this->User_GetUserCurrent()->getAccess( "node".$iNodeId );
			if( $sUserAccess===false ) $sUserAccess = $this->User_GetUserCurrent()->getAccess("content");
		}
		if( $sUserAccess >= $sAccessNeeded ) return true;
		$this->AccessDenied();
		return false;
	}

	public function GetTemplate(){
		return Router::GetCurrentTemplate();
	}
	
	public function SetTemplate($sTemplate){
		$sClassName = get_class($this);

		if($sClassName=="ComponentAdmin"){
			Router::SetCurrentTemplate("components/".strtolower(str_replace("Component", "", $sClassName))."/templates/default/".$sTemplate);
		}elseif (preg_match("/^Component(\w+)Admin$/i",$sClassName,$aMatch)){
			Router::SetCurrentTemplate("components/".strtolower($aMatch[1])."/templates/admin/".$sTemplate);
		}else{
			$sSkin = Router::GetCurrentNode()->GetParam("template");
			Router::SetCurrentTemplate("components/".strtolower(str_replace("Component", "", $sClassName))."/templates/skins/".$sSkin."/".$sTemplate);
		}
	}
	
	// public function GetParamsCurrent($oNode){
	// 	$this->aParamsCurrent = $this->Component_GetParamsByNode($oNode);
	// 	return $this->aParamsCurrent;
	// }

	// public function GetParamsDefault($sComponent){
	// 	if(!$sComponent){
	// 		preg_match("/^Component(\w+?)(?:Admin$|$)/i",get_class($this),$aMatch);
	// 		$sComponent = $aMatch[1];
	// 	}
	// 	$sFilePath = "components/".(mb_strtolower($sComponent))."/config/config.php";
	// 	if(file_exists($sFilePath))
	// 		include_once($sFilePath);
	// 	return $this->aParamsDefault;
	// }

	// public function SetParams($aParams){
	// 	return $this->aParamsDefault = $aParams;
	// }
	
	// public function GetParam($sVar){
	//  	return $this->aParamsCurrent[$sVar] ? $this->aParamsCurrent[$sVar] : $this->aParamsDefault[$sVar]["default"];
	// }

	
	public function __call($sName,$aArgs) {
		return $this->oEngine->_CallModule($sName,$aArgs);
	}
}