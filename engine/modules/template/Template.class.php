<?
require_once('engine/libs/'.Config::Get("template.smarty.version").'/libs/Smarty.class.php');
define('SMARTY_DIR', 'engine/libs/'.Config::Get("template.smarty.version").'/libs/');

class ModuleTemplate extends Module {
	private $oDb=null;
	private $oSmarty=null;
	private $sTitle=null;
	private $aBreadCrumbs = array();
	private $sDescription=null;
	private $sKeywords=null;
	private $aCss=array();
	private $aJs=array();
	private $sHost=null;
	private $sPath=null;
	private $sPageTitle=null;
	private $aMessages=array();
	private $sResult=null;
	private $aTemplates=array();
	private $aConditionTypes=array(
			"get"=>"Get-переменаая",
			"node"=>"Раздел",
			"nodetree"=>"Раздел и подразделы"
		);
	
	public function Init($sTemplate){
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
		$this->oSmarty = new Smarty();
		$this->oSmarty->debugging = Config::Get("template.smarty.debug");
		$this->oSmarty->caching = Config::Get("template.smarty.cache.use");
		$this->oSmarty->cache_lifetime = Config::Get("template.smarty.lifetime");
		
		$this->oSmarty->template_dir = Config::Get("template.path");
		$this->oSmarty->compile_dir = Config::Get("template.smarty.compile");
		$this->oSmarty->cache_dir =  Config::Get("template.smarty.cache.dir");
		$this->oSmarty->plugins_dir[] =  Config::Get("template.smarty.plugins.dir");
		
		$oNode = Router::GetCurrentNode();
		$this->SetKeywords($oNode->getSeoKeywords());
		$this->SetDescription($oNode->getSeoDescription());
		if( $oNode->getSeoTitle() ) 
			$this->SetTitle($oNode->getSeoTitle());
		else{
			$this->SetTitle(Config::Get("html.title"));
			$this->AddTitle($oNode->getTitle());
		}
		$this->SetPageTitle($oNode->getTitle());

		$this->SetHost(Config::Get("host"));
		$this->SetPath(Config::Get("host").Config::Get("template.path"));
		$this->AddCss($this->GetPath()."css/reset.css");
		$this->AddCss($this->GetPath()."css/styles.css");
	}
	public function AssignVars(){
		$this->oSmarty->assign("aTemplate", array(
			"title" => $this->GetTitle(),
			"meta" => $this->GetMetaHtml(),
			/*"css" => $this->GetCssHtml(),
			"js" => $this->GetJsHtml(),*/
			"host" => $this->GetHost(),
			"path" => $this->GetPath(),
			"page_title" => $this->GetPageTitle(),
			"node_url" => ($this->GetHost().Router::GetNodeUrl()),
			"messages" => $this->GetMessages()
		));
		$this->oSmarty->assign("aRequest", $_REQUEST);
		$this->oSmarty->assign("oCurrentNode", Router::GetCurrentNode());
		$this->Template_Assign("oAdminUser", $this->User_GetUserCurrent());
	}
	public function LazyAssign(){
		$this->sResult = str_replace(array(
			"==css==",
			"==js=="
		), 
		array(
			$this->GetCssHtml(),
			$this->GetJsHtml()
		),
		$this->sResult);
	}
	public function Assign($sVar, $val){
		$this->oSmarty->assign($sVar, $val);
	}
	public function Fetch($sTemplate) {
		return $this->oSmarty->fetch($sTemplate);
	}
	public function ClearAllAssign(){
		$this->oSmarty->clearAllAssign();
	}
	public function Display($sPath){
		$this->AssignVars();
		$this->sResult = $this->oSmarty->fetch($sPath);
		$this->LazyAssign();
		echo $this->sResult;
	}
	
	public function SetTitle($sTitle) {
		$this->sTitle=$sTitle;
	}
	public function AddTitle($sTitle) {
		$this->sTitle.=Config::Get("html.separator").$sTitle;
	}
	public function GetTitle() {
		return $this->sTitle;
	}
	public function AddBreadCrumb($sHref, $sTitle) {
		$this->aBreadCrumbs[] = array("href"=>$sHref,
			"title"=>$sTitle
		);
	}
	public function GetBreadCrumbs() {
		return $this->aBreadCrumbs;
	}
	public function SetDescription($sData) {
		$this->sDescription=$sData;
	}
	public function GetDescription() {
		return $this->sDescription;
	}
	
	public function SetKeywords($sData) {
		$this->sKeywords=$sData;
	}
	public function GetKeywords() {
		return $this->sKeywords;
	}
	
	public function SetPath($sData) {
		$this->sPath=$sData;
	}
	public function GetPath() {
		return $this->sPath;
	}
	
	public function SetHost($sData) {
		$this->sHost=$sData;
	}
	public function GetHost() {
		return $this->sHost;
	}
	
	public function SetCss($sCss) {
		$this->aCss=array($sCss);
	}
	public function AddCss($sCss) {
		if( !in_array($sCss, $this->aCss) ) $this->aCss[]=$sCss;
	}
	public function GetCssHtml() {
		$sCss="";
		foreach($this->aCss as $sData){
			$sCheckCss = $this->GetSystemPath($sData);
			if( file_exists( $sCheckCss ) )
				$sCss.='<link rel="StyleSheet" href="'.$sData.'" type="text/css">'."\n\r";
			else
				$sCss.='<!-- StyleSheet "'.$sData.'" not found on server -->'."\n\r";
		}
		return $sCss;
	}
	public function GetMetaHtml() {
		$sMeta ='<meta name="description" content="'.$this->GetDescription().'">';
		$sMeta.='<meta name="keywords" content="'.$this->GetKeywords().'">';
		return $sMeta;
	}
	
	public function SetJs($sJs) {
		$this->aJs=array($sJs);
	}
	public function AddJs($sJs) {
		if( !in_array($sJs, $this->aJs) ) $this->aJs[]=$sJs;
	}
	public function ReplaceJs($iIndex, $sJs) {
		$this->aJs[$iIndex]=$sJs;
	}
	public function GetJsHtml() {
		$sJs="";
		foreach($this->aJs as $sData){
			$sCheckJs = $this->GetSystemPath($sData);
			if( file_exists( $sCheckJs ) )
				$sJs.='<script type="text/javascript" src="'.$sData.'"></script>';
			else
				$sJs.='<!-- Srcipt "'.$sData.'" not found on server -->'."\n\r";
		}
		return $sJs;
	}
	
	public function SetPageTitle($sData) {
		$this->sPageTitle=$sData;
	}
	public function GetPageTitle() {
		return $this->sPageTitle;
	}
	
	public function AddMessage($sTitle, $sMsg) {
		$this->aMessages[]=array("title"=>$sTitle, "msg"=>$sMsg);
	}
	public function GetMessages() {
		return $this->aMessages;
	}
	public function SetTemplate($sTemplateName){
		$sDir = "templates/".$sTemplateName;
		if( file_exists($sDir) ) $this->oSmarty->template_dir = $sDir;
	}
	public function DefineTemplate(){
		$aArgvNodeIds = array_map(function($oNode){return $oNode->getId();}, Router::GetArgvNodes());
		$aConditions = $this->GetConditionsList();
		foreach ($aConditions as $oCondition) {

			switch ($oCondition->getType()) {
				case 'get':
					$sGetVar = getRequest($oCondition->getVar(),"get");
					if(	isset($sGetVar) &&	( $sGetVar == $oCondition->getVal() || !$oCondition->getVal() ) ){
						$this->SetTemplate($oCondition->getTemplate());
						return;
					}
					break;
				case 'node':
					if( Router::GetCurrentNode()->GetId() == $oCondition->getNode() ){
						$this->SetTemplate($oCondition->getTemplate());
						return;
					}
					break;
				case 'nodetree':
					if( in_array($oCondition->getNode(), $aArgvNodeIds) ){
						$this->SetTemplate($oCondition->getTemplate());
						return;
					}
					break;
			}
		}
	}

	public function GetTemplatesAlailable(){
		$aFolders = glob("templates/*", GLOB_ONLYDIR);
		foreach ($aFolders as $sFolder){
			$sTemplateDesc = $sFolder."/description.php";
			if( file_exists($sTemplateDesc) ){
				include_once( $sTemplateDesc );
				
			}
		}
		return $this->aTemplates;
	}
	private function AddTemplateDesc($sName, $aArray){
		$oTemplate = Engine::GetEntity("Template", $aArray);
		$oTemplate->setName($sName);
		$this->aTemplates[$sName] = $oTemplate;
	}

	public function GetConditionTypes(){
		return $this->aConditionTypes;
	}
	public function GetConditionById($iId){
		if (false === ($data = $this->Cache_Get("condition_{$iId}"))) {
			$data=$this->oDb->GetConditionById($iId);
			$this->Cache_Set("conditions_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetConditionsList(){
		if (false === ($data = $this->Cache_Get("conditions_list"))) {	
			$aResult=$this->oDb->GetConditionsList(); //только id
			$data = array();
			foreach ($aResult as $iId) {
				$data[] = $this->GetConditionById($iId);
			}
			$this->Cache_Set("conditions_list", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function AddCondition(ModuleTemplate_EntityCondition $oCondition) {
		$this->Cache_Delete("conditions_list");
		if ($iId=$this->oDb->AddCondition($oCondition)){
			$oCondition->setId($iId);
		}
		return $oCondition;
	}
	public function UpdateCondition(ModuleTemplate_EntityCondition $oCondition) {
		$this->Cache_Delete("conditions_list");
		$this->Cache_Delete("conditions_{$oCondition->getId()}");
		return $this->oDb->UpdateCondition($oCondition);
	}
	public function DeleteCondition($iId) {
		$this->Cache_Delete("conditions_list");
		$this->Cache_Delete("conditions_{$iId}");
		return $this->oDb->DeleteCondition($iId);
	}
	public function ActivateCondition($iConditionId) {
		$this->Cache_Delete("conditions_{$iConditionId}");
		return $this->oDb->ActivateCondition($iConditionId);
	}
	public function DeactivateCondition($iConditionId) {
		$this->Cache_Delete("conditions_{$iConditionId}");
		return $this->oDb->DeactivateCondition($iConditionId);
	}

	private function GetSystemPath($sPath){
		$sPath = str_replace(Config::Get("host"), '', $sPath);
		$sPath = ltrim($sPath, '/');
		return $sPath;
	}
}