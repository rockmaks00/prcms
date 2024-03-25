<?
/*
пример использования
name - обязательный параметр, имя Hooka
template - обязательный параметр, имя шаблона
showroot - необязательный параметр, показывать ли ссылку на главную
{hook name="breadcrumbs" template="default" showroot=1}
*/
class HookBreadcrumbs extends Hook {
	protected $aArgv = array();
	protected $aParams;
	public function Init() {}

	protected function BacktraceArgv($oNode){
		if($this->aParams["showroot"] or $oNode->getId() > 1){
			$aCrumb = array(
				"node"=>$oNode,
				"href"=>$oNode->getFullUrl(),
				"title"=>$oNode->getTitle()
			);
			array_unshift($this->aArgv, $aCrumb);
		}
		if($iParentId = $oNode->getParent()) 
			$oParentNode = $this->Node_GetNodeById( $iParentId );
		if($oParentNode)
			$this->BacktraceArgv($oParentNode);
	}

	public function Breadcrumbs($aParams) {
		$this->aParams = $aParams;
		$this->BacktraceArgv(Router::getCurrentNode());
		$aAddedCrumbs = $this->Template_GetBreadCrumbs();
		$aCrumbs = array_merge($this->aArgv, $aAddedCrumbs);
		$this->Template_Assign('aCrumbs', $aCrumbs);
		return $this->Template_Fetch("hooks/breadcrumbs/templates/".$aParams['template'].".tpl");
	}
}